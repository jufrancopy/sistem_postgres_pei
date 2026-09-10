@extends('layouts.master')
@section('title', 'Evaluación — ' . $est->nombre_oficial)

@push('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet"/>
<style>
.select2-container--default .select2-selection--multiple { 
    border:1px solid #ced4da; 
    border-radius:6px; 
    min-height:42px !important; 
    height: 42px !important; /* Force height for single row */
    padding: 0 8px !important; 
    display: flex !important; 
    align-items: center !important; 
    overflow: hidden !important;
}

.select2-container--default.select2-container--focus .select2-selection--multiple,
.select2-container--default.select2-container--open .select2-selection--multiple {
    height: auto !important; /* Allow growth only when active/focused if many choices */
    min-height: 42px !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered { 
    display: flex !important; 
    flex-wrap: nowrap !important; /* Keep single line by default */
    gap: 4px; 
    padding: 0 !important; 
    margin: 0 !important; 
    list-style: none; 
    align-items: center; 
    width: 100%;
}

.select2-container--default.select2-container--focus .select2-selection__rendered,
.select2-container--default.select2-container--open .select2-selection__rendered {
    flex-wrap: wrap !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice { 
    background:#e91e63; 
    border:none; 
    color:#fff; 
    border-radius:20px; 
    padding:1px 10px; 
    font-size:.78rem; 
    margin: 0 !important; 
    line-height: 1.5;
}

/* Search field idle state */
.select2-search--inline { margin: 0 !important; padding: 0 !important; height: 100% !important; display: flex !important; align-items: center !important; }
.select2-search__field { margin: 0 !important; height: 30px !important; line-height: 30px !important; }

.select2-container--default .select2-selection--single { border:1px solid #ced4da; border-radius:6px; height:42px !important; padding:6px 8px; display: flex; align-items: center; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: normal !important; padding-left: 0 !important; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height:40px !important; }

/* Standard inputs height sync */
#evalTelefono, #evalFecha { height: 42px !important; border: 1px solid #ced4da !important; border-radius: 6px !important; padding: 6px 8px !important; }

/* Fix for Select2 search on mobile and modals */
.select2-container--open { z-index: 99999 !important; }
.select2-dropdown { z-index: 99999 !important; border-radius:8px; border:1px solid #e5e7eb; box-shadow:0 4px 16px rgba(0,0,0,.1); }

/* Responsive Fixes for Select2 */
.select2-container { width: 100% !important; display: block; }
.select2-selection { width: 100% !important; }

/* Force search field to be reachable but not intrusive */
.select2-container .select2-search--inline { 
    display: inline-block !important; 
    vertical-align: middle !important;
}
.select2-container .select2-search--inline .select2-search__field { 
    width: auto !important;
    min-width: 30px !important; /* Minimal touch area */
    max-width: 100% !important;
    margin-top: 0 !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
}

/* Grow and highlight ONLY when focused or searching */
.select2-container--focus .select2-search__field,
.select2-container--open .select2-search__field {
    min-width: 150px !important;
    background-color: #fff !important;
    border: 1px solid #e91e63 !important; /* Highlight color */
    border-radius: 4px !important;
    padding: 2px 8px !important;
}

/* Material Design Fixes */
.bmd-form-group { padding-top: 0 !important; margin-bottom: 0 !important; }
.form-group { margin-bottom: 0 !important; }

/* Modal Cierre Firmas anti-overlap styles */
#modalCierreFirmas .bmd-form-group,
#modalCierreFirmas .form-group {
    position: relative !important;
    margin-bottom: 1.15rem !important;
    padding-top: 0 !important;
}
#modalCierreFirmas label,
#modalCierreFirmas .bmd-label-floating,
#modalCierreFirmas .bmd-label-static,
#modalCierreFirmas .control-label {
    position: static !important;
    transform: none !important;
    top: auto !important;
    left: auto !important;
    display: block !important;
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    margin-bottom: 0.35rem !important;
    pointer-events: auto !important;
    opacity: 1 !important;
}
#modalCierreFirmas .form-control {
    position: static !important;
    display: block !important;
    width: 100% !important;
    height: 38px !important;
    padding: 0.45rem 0.75rem !important;
    font-size: 0.88rem !important;
    line-height: 1.5 !important;
    color: #0f172a !important;
    background-color: #ffffff !important;
    background-image: none !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    box-shadow: none !important;
}
#modalCierreFirmas textarea.form-control {
    height: auto !important;
}
#modalCierreFirmas .form-control:focus {
    border-color: #0284c7 !important;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
}

/* Reset Material Design backgrounds on Select2 inputs */
.select2-search__field {
    background-image: none !important;
    background-color: #fff !important;
    border: 1px solid #ced4da !important;
    padding: 6px 10px !important;
    margin-top: 5px !important;
    box-shadow: none !important;
    outline: none !important;
    width: 100% !important;
    pointer-events: auto !important;
    display: inline-block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ensure the dropdown is above everything and interactive */
.select2-container--open { z-index: 999999 !important; pointer-events: auto !important; }
.select2-dropdown { 
    z-index: 999999 !important; 
    pointer-events: auto !important;
    border-radius: 8px; 
    border: 1px solid #e5e7eb; 
    box-shadow: 0 4px 16px rgba(0,0,0,0.15); 
}

/* Force interactivity on all Select2 parts */
.select2-selection, .select2-results, .select2-results__option {
    pointer-events: auto !important;
}

/* Responsive Fixes for Select2 */
.select2-container { width: 100% !important; display: block; }
.select2-selection { width: 100% !important; min-height: 42px !important; }

/* Force search field width in multi-select */
.select2-container .select2-search--inline { width: 100% !important; display: block !important; }
.select2-container .select2-search--inline .select2-search__field { 
    width: 100% !important; 
    min-width: 100px !important; 
    margin-left: 0 !important;
}

/* Neutralize Material Design focus/transition effects */
.bmd-form-group .select2-container { position: relative; }
.bmd-form-group .select2-container::before, 
.bmd-form-group .select2-container::after { display: none !important; }

@media (max-width: 768px) {
    .select2-container { margin-bottom: 15px; }
    .select2-search__field { font-size: 16px !important; height: 40px !important; }
}

#evalWrapper { display:flex; gap:16px; align-items:flex-start; }
#sidebarSecciones { width:260px; flex-shrink:0; position:sticky; top:80px; max-height:calc(100vh - 100px); overflow-y:auto; overflow-x:visible; }
#contenidoFormulario { flex:1; min-width:0; }

/* Ensure the card and rows don't clip the Select2 dropdown */
.card, .card-body, .row { overflow: visible !important; }

/* Critical: stable coordinate system for Select2 at 767px stacking */
.col-md-3, .col-md-4, .col-md-6, .col-12 { 
    position: relative !important; 
    overflow: visible !important; 
}

/* Prevent jumping to top on mobile */
.select2-container--open .select2-dropdown {
    margin-top: -1px; /* Align perfectly with input */
}

.sec-item { display:flex; align-items:center; gap:8px; padding:7px 12px; border-radius:8px; cursor:pointer; font-size:.82rem; transition:background .15s; border:none; background:none; width:100%; text-align:left; }
.sec-item:hover { background:#f8f8f8; }
.sec-item.active { background:#fce4ec; color:#c62828; font-weight:600; }
.sec-item .sec-dot { width:8px; height:8px; border-radius:50%; background:#e0e0e0; flex-shrink:0; }
.sec-item.completa .sec-dot { background:#22c55e; }
.sec-item.parcial  .sec-dot { background:#f97316; }

.seccion-card { border-radius:12px; border:1px solid #e5e7eb; margin-bottom:20px; overflow:hidden; }
.seccion-card .seccion-header { background:#f9fafb; padding:14px 18px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; gap:10px; }
.seccion-card .seccion-body { padding:18px; }

.pregunta-item { padding:12px 0; border-bottom:1px solid #f3f4f6; }
.pregunta-item:last-child { border-bottom:none; }
.pregunta-label { font-size:.88rem; font-weight:500; margin-bottom:8px; }
.pregunta-label .req-star { color:#e91e63; }

.resp-group { display:flex; gap:8px; flex-wrap:wrap; }
.resp-btn { padding:5px 14px; border-radius:20px; border:1.5px solid #e0e0e0; background:#fff; font-size:.8rem; cursor:pointer; transition:all .15s; }
.resp-btn:hover { border-color:#e91e63; color:#e91e63; }
.resp-btn.selected-si  { background:#d1fae5; border-color:#22c55e; color:#065f46; font-weight:600; }
.resp-btn.selected-no  { background:#fee2e2; border-color:#ef4444; color:#991b1b; font-weight:600; }
.resp-btn.selected-na  { background:#f1f5f9; border-color:#94a3b8; color:#475569; font-weight:600; }

#barraProgreso { height:8px; border-radius:4px; background:rgba(255,255,255,.3); overflow:hidden; }
#barraFill { height:100%; border-radius:4px; background:#fff; transition:width .3s; }

#panelResultado { display:none; }
.resultado-card { border-radius:16px; padding:24px; text-align:center; }
.resultado-CUMPLE              { background:linear-gradient(135deg,#d1fae5,#a7f3d0); }
.resultado-CUMPLE_PARCIALMENTE { background:linear-gradient(135deg,#fef3c7,#fde68a); }
.resultado-NO_CUMPLE           { background:linear-gradient(135deg,#fee2e2,#fecaca); }

@media(max-width:768px) {
    #evalWrapper { flex-direction:column; }
    #sidebarSecciones { width:100%; position:static; max-height:none; }
}
</style>
@endpush

@section('content')
{{-- Header --}}
<div class="card mb-3">
    <div class="card-header card-header-info py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0">
                    <i class="fa fa-clipboard-check mr-2"></i>{{ $est->nombre_oficial }}
                </h4>
                <p class="card-category mb-0">{{ $est->tipologia_clasificacion }} — {{ $est->complejidad }}</p>
            </div>
            <div class="text-right">
                <div id="barraProgreso" style="width:160px;margin-bottom:4px;height:8px;border-radius:4px;background:rgba(255,255,255,.3);overflow:hidden">
                    <div id="barraFill" style="height:100%;width:0%;border-radius:4px;background:#fff;transition:width .3s"></div>
                </div>
                <small class="text-white" id="txtProgreso">Cargando formulario...</small>
            </div>
        </div>
    </div>
    <nav class="bg-light px-3 py-2">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Evaluación</li>
        </ol>
    </nav>
</div>

<div id="evalWrapper">

    {{-- Sidebar --}}
    <div id="sidebarSecciones">
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <div class="px-2 mb-2">
                    <select id="buscadorPreguntas" style="width:100%"></select>
                </div>
                <div class="small font-weight-bold text-uppercase text-muted px-2 mb-2">Secciones</div>
                <div id="listaSecciones">
                    <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    <div id="contenidoFormulario">

        {{-- Card unificado: Datos del establecimiento + Visita --}}
        <div class="card shadow-sm mb-4" id="cardDatosEstablecimiento">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3">
                    <i class="fa fa-hospital mr-2 text-danger"></i>Datos del establecimiento
                    <small class="text-muted font-weight-normal ml-2" id="evalEstado"></small>
                </h6>

                <div class="row">
                    {{-- Nombre (prellenado, solo lectura) --}}
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Nombre del establecimiento</label>
                        <input type="text" class="form-control" value="{{ $est->nombre_oficial }}" readonly
                               style="background:#f9fafb;color:#374151;font-weight:600">
                    </div>
                    {{-- Razón Social --}}
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Nombre o Razón Social</label>
                        <input type="text" id="estRazonSocial" class="form-control"
                               placeholder="Nombre oficial o razón social..."
                               value="{{ $est->nm_empresa_costos ?? '' }}">
                    </div>
                    {{-- Dirección --}}
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Dirección del establecimiento</label>
                        <input type="text" id="estDireccion" class="form-control" placeholder="Dirección completa...">
                    </div>
                    {{-- Teléfono --}}
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Teléfono</label>
                        <input type="text" id="evalTelefono" class="form-control" placeholder="0981...">
                    </div>
                    {{-- Email --}}
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Correo electrónico</label>
                        <input type="email" id="estEmail" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                </div>

                <hr class="my-3">

                {{-- Evaluadores y fecha --}}
                <h6 class="font-weight-bold mb-3 text-muted small text-uppercase">
                    <i class="fa fa-user-check mr-1"></i>Datos de la visita
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Evaluadores <span class="text-danger">*</span></label>
                        <select id="evalEvaluadores" class="form-control" multiple style="width:100%"></select>
                        <small class="text-muted">Uno o más evaluadores</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold"><i class="fa fa-bullseye text-info mr-1"></i> Plan PEI / Marco Estratégico</label>
                        <select id="evalPeiProfile" class="form-control" style="width:100%"></select>
                        <small class="text-muted">Asocia esta evaluación a un Plan PEI específico</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Fecha de evaluación <span class="text-danger">*</span></label>
                        <input type="date" id="evalFecha" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold">Enlace de ubicación</label>
                        <input type="text" id="estMapLink" class="form-control" placeholder="https://maps.google.com/...">
                    </div>
                </div>

                <hr class="my-3">

                {{-- Ubicación --}}
                <h6 class="font-weight-bold mb-2 text-muted small text-uppercase">
                    <i class="fa fa-map-marker-alt mr-1"></i>Ubicación del establecimiento
                </h6>
                @include('admin.riiss.partials.localidad_select', ['prefix' => 'eval', 'required' => false])
            </div>
        </div>

        {{-- Card de Especialidades Médicas del Establecimiento --}}
        <div class="card shadow-sm mb-4 border-0" id="cardEspecialidadesEstablecimiento" style="border-radius:14px; border: 1px solid #e2e8f0;">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light p-2 mr-3 text-primary d-flex align-items-center justify-content-center" style="width:38px; height:38px;">
                        <i class="fa fa-stethoscope fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0" style="font-size:0.95rem;">
                            Especialidades Médicas del Establecimiento
                            <span class="badge badge-pill badge-primary ml-2 font-weight-bold" id="badgeEspecialidadesCount">{{ $est->especialidades->count() }}</span>
                        </h6>
                        <small class="text-muted">Servicios y prestaciones de especialidades médicas activas en este centro</small>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-xs" id="btnToggleAddEspecialidad" onclick="$('#panelAgregarEspecialidad').slideToggle(200); setTimeout(function(){ $('#selectNuevaEspecialidad').select2('open'); }, 250);" style="border-radius:8px;">
                        <i class="fa fa-plus-circle mr-1"></i> Agregar Especialidad
                    </button>
                </div>
            </div>

            {{-- Formulario para Agregar Especialidad (Colapsable) --}}
            <div id="panelAgregarEspecialidad" class="p-3 bg-light border-bottom" style="display:none;">
                <div class="row align-items-center" style="gap: 8px;">
                    <div class="col-md-7 mb-2 mb-md-0">
                        <label class="font-weight-bold text-dark small mb-1">Buscar o escribir nombre de la especialidad:</label>
                        <select id="selectNuevaEspecialidad" class="form-control" style="width:100%"></select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end pt-md-4" style="gap:8px;">
                        <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" id="btnConfirmarAddEsp" onclick="agregarEspecialidad()" style="border-radius:8px;">
                            <i class="fa fa-check mr-1"></i> Vincular Especialidad
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#panelAgregarEspecialidad').slideUp(200);" style="border-radius:8px;">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 bg-white">
                <div id="contenedorEspecialidadesPills" class="d-flex flex-wrap" style="gap:8px;">
                    <div class="text-center w-100 py-3 text-muted"><div class="spinner-border spinner-border-sm text-primary mr-2"></div>Cargando especialidades...</div>
                </div>
            </div>
        </div>

        {{-- Secciones del formulario --}}
        <div id="seccionesFormulario"></div>

        {{-- Aspectos Positivos Observados --}}
        <div class="card shadow-sm mb-4 border-success" id="panelAspectosPositivos" style="display:none">
            <div class="card-header bg-success text-white py-2">
                <h6 class="mb-0 font-weight-bold text-uppercase text-white" style="font-size:0.85rem">
                    <i class="fa fa-star mr-1"></i>Aspectos Positivos Observados
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">Registrá las prácticas destacadas, fortalezas, innovaciones o aspectos positivos observados en el establecimiento.</p>
                <textarea id="evalAspectosPositivos" class="form-control"></textarea>
            </div>
        </div>

        {{-- Observaciones Generales --}}
        <div class="card shadow-sm mb-4" id="panelObservaciones" style="display:none">
            <div class="card-header bg-light py-2 border-bottom">
                <h6 class="mb-0 font-weight-bold text-uppercase" style="font-size:0.85rem">
                    <i class="fa fa-comment-dots mr-1 text-info"></i>Sugerencias u Observaciones Generales
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">Utilizá este espacio libre para registrar observaciones, sugerencias o contexto adicional de la visita.</p>
                <textarea id="evalObservaciones" class="form-control"></textarea>
                <div class="mt-3 text-right">
                    <button class="btn btn-sm btn-info" id="btnGuardarObs" onclick="guardarObservaciones()">
                        <i class="fa fa-save mr-1"></i>Guardar Observaciones y Aspectos Positivos
                    </button>
                </div>
            </div>
        </div>

        {{-- Evidencia Fotográfica / Galería de Fotos del Relevamiento --}}
        <div class="card shadow-sm mb-4" id="panelFotosRelevamiento" style="display:none; border-radius:12px; overflow:hidden;">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light p-2 mr-3 text-primary d-flex align-items-center justify-content-center" style="width:38px; height:38px;">
                        <i class="fa fa-camera fa-lg text-info"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-bold text-dark" style="font-size:0.95rem">
                            📸 Evidencia Fotográfica del Relevamiento en Terreno
                        </h6>
                        <small class="text-muted">Galería fotográfica con descripción técnica (mínimo 3 fotos: Fachada, Urgencias/Servicios, Farmacia/Depósito).</small>
                    </div>
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <span class="badge badge-light border text-dark font-weight-bold px-3 py-2" id="badgeContadorFotos" style="font-size:0.8rem; border-radius:8px;">
                        <i class="fa fa-images text-info mr-1"></i> <span id="txtCantidadFotos">0</span> fotos cargadas
                    </span>
                    <button type="button" class="btn btn-info btn-sm font-weight-bold shadow-sm" onclick="$('#inputSubirFoto').click()" style="border-radius:8px;">
                        <i class="fa fa-camera mr-1"></i> Capturar / Subir Foto
                    </button>
                    <input type="file" id="inputSubirFoto" accept="image/*" style="display:none" onchange="subirNuevaFoto(this)">
                </div>
            </div>
            <div class="card-body p-4 bg-light">
                
                {{-- Formulario de carga con descripción --}}
                <div id="dropzoneNuevaFoto" class="p-3 mb-3 bg-white rounded border text-center shadow-xs" style="border: 2px dashed #cbd5e1 !important; border-radius:12px;">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-5 mb-2 mb-md-0">
                            <input type="text" id="descNuevaFotoInput" class="form-control form-control-sm" placeholder="📝 Descripción técnica (ej: Fachada principal, Farmacia, Quirófano, Consultorios)...">
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="custom-file text-left">
                                <input type="file" class="custom-file-input" id="inputArchivoFotoDirecta" accept="image/*">
                                <label class="custom-file-label small" for="inputArchivoFotoDirecta" id="labelArchivoFoto">Seleccionar imagen...</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary btn-sm btn-block font-weight-bold" id="btnSubirFotoManual" onclick="ejecutarSubidaFotoManual()" style="border-radius:6px;">
                                <i class="fa fa-cloud-upload-alt mr-1"></i> Guardar Foto
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Contenedor dinámico de la Galería de Fotos --}}
                <div class="row" id="contenedorGaleriaFotos"></div>
            </div>
        </div>

        {{-- Botones --}}
        <div id="botonesAccion" class="card shadow-sm mb-4" style="display:none">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                <span class="text-muted small" id="txtRespuestas">0 respuestas</span>
                <div class="d-flex align-items-center flex-wrap" style="gap:8px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" onclick="guardarParcial()">
                        <i class="fa fa-save mr-1"></i>Guardar parcial
                    </button>
                    <button type="button" class="btn btn-danger btn-sm font-weight-bold shadow-sm" onclick="abrirModalCierreFirmas()">
                        <i class="fa fa-file-signature mr-1"></i> Finalizar y Sellar con Firmas
                    </button>
                </div>
            </div>
        </div>

        {{-- Resultado --}}
        <div id="panelResultado" class="mb-4"></div>

    </div>
</div>

{{-- Modal Acta de Cierre y Firmas Digitales --}}
<div class="modal fade" id="modalCierreFirmas" tabindex="-1" role="dialog" aria-labelledby="modalCierreFirmasTitle" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header text-white py-3 px-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div>
                    <span class="badge badge-light text-dark font-weight-bold mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">
                        <i class="fa fa-file-signature text-info mr-1"></i> ACTA DE CIERRE EN TERRENO
                    </span>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalCierreFirmasTitle" style="font-size: 1.15rem;">
                        Acta de Cierre y Firmas Digitales del Relevamiento RIISS
                    </h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc; max-height: 80vh; overflow-y: auto;">
                
                {{-- Resumen del Establecimiento --}}
                <div class="alert bg-white border shadow-xs d-flex align-items-center justify-content-between mb-4 flex-wrap" style="border-radius:12px; border-left: 5px solid #0284c7 !important; gap:10px;">
                    <div>
                        <small class="text-muted text-uppercase font-weight-bold" style="font-size:0.7rem;">Establecimiento Relevado</small>
                        <h6 class="font-weight-bold text-dark mb-0">{{ $est->nombre_oficial }}</h6>
                        <small class="text-info">{{ $est->tipologia_clasificacion }} — {{ $est->complejidad }}</small>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-primary px-3 py-2" style="font-size:0.8rem; border-radius:8px;">
                            <i class="fa fa-calendar-check mr-1"></i> Fecha: {{ date('d/m/Y') }}
                        </span>
                    </div>
                </div>

                <form id="formCierreFirmas">
                    <div class="row">
                        {{-- BLOQUE 1: Responsable del Establecimiento (Receptor) --}}
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border: 1px solid #e2e8f0;">
                                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 mr-2 text-primary d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                                            <i class="fa fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0" style="font-size:0.95rem;">1. Responsable del Establecimiento</h6>
                                            <small class="text-muted">Persona que recibe al equipo en el centro</small>
                                        </div>
                                    </div>
                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-1" style="font-size:0.68rem;">Receptor</span>
                                </div>
                                <div class="card-body p-4 bg-white">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark small mb-1">Nombre y Apellido <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="cierre_responsable_nombre" name="responsable_nombre" placeholder="Ej: Dra. Carmen López / Lic. Marcos Benítez" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label class="font-weight-bold text-dark small mb-1">Cargo / Función <span class="text-danger">*</span></label>
                                            <input type="text" list="listaCargosSugeridos" class="form-control form-control-sm" id="cierre_responsable_cargo" name="responsable_cargo" placeholder="Ej: Directora Médica" required>
                                            <datalist id="listaCargosSugeridos">
                                                <option value="Director/a Médico/a">
                                                <option value="Administrador/a del Establecimiento">
                                                <option value="Lic. en Enfermería / Jefa de Enfermería">
                                                <option value="Jefe de Guardia / Admisión">
                                                <option value="Encargado/a de Farmacia">
                                                <option value="Asistente Administrativo/a">
                                                <option value="Coordinador/a de Área">
                                            </datalist>
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label class="font-weight-bold text-dark small mb-1">Cédula de Identidad (C.I.)</label>
                                            <input type="text" class="form-control form-control-sm" id="cierre_responsable_documento" name="responsable_documento" placeholder="Ej: 1.234.567">
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark small mb-1">Teléfono de Contacto</label>
                                        <input type="text" class="form-control form-control-sm" id="cierre_responsable_telefono" name="responsable_telefono" placeholder="Ej: 0981 123 456">
                                    </div>

                                    {{-- Recuadro Canvas Firma Responsable --}}
                                    <div class="form-group mb-0">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <label class="font-weight-bold text-dark small mb-0"><i class="fa fa-pen-alt mr-1 text-primary"></i>Firma Digital del Receptor <span class="text-danger">*</span></label>
                                            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" id="btnClearFirmaResponsable" style="font-size:0.72rem; border-radius:6px;">
                                                <i class="fa fa-eraser mr-1"></i>Limpiar
                                            </button>
                                        </div>
                                        <div class="border rounded bg-light d-flex align-items-center justify-content-center p-1" style="border: 2px dashed #94a3b8 !important; border-radius: 10px; background:#fafafa;">
                                            <canvas id="canvasFirmaResponsable" width="480" height="150" style="touch-action: none; width: 100%; height: 150px; background: #ffffff; border-radius: 8px; cursor: crosshair;"></canvas>
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size:0.72rem;"><i class="fa fa-info-circle mr-1"></i>Dibujar la firma directamente en el recuadro táctil o con el cursor.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE 2: Evaluadores / Equipo de Relevamiento IPS --}}
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100" style="border-radius:14px; border: 1px solid #e2e8f0;">
                                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 mr-2 text-info d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                                            <i class="fa fa-user-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-0" style="font-size:0.95rem;">2. Equipo Técnico Evaluador IPS</h6>
                                            <small class="text-muted" id="txtResumenEvaluadoresModal">Talento humano comisionado de Planificación</small>
                                        </div>
                                    </div>
                                    <span class="badge badge-info text-white font-weight-bold px-2 py-1" style="font-size:0.68rem;">Equipo IPS</span>
                                </div>
                                <div class="card-body p-3 bg-white" style="max-height: 520px; overflow-y: auto;">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark small mb-1">Observaciones Finales de Cierre (Opcional)</label>
                                        <textarea class="form-control form-control-sm" id="cierre_observaciones_cierre" name="cierre_observaciones" rows="2" placeholder="Notas sobre la visita, acuerdos o condiciones observadas..."></textarea>
                                    </div>

                                    {{-- Contenedor dinámico de firmas de los evaluadores comisionados --}}
                                    <div id="contenedorFirmasEvaluadoresModal">
                                        {{-- Generado dinámicamente según evaluadores seleccionados --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Declaración de Conformidad --}}
                    <div class="p-3 bg-white border rounded d-flex align-items-center flex-wrap" style="border-radius:12px !important; gap:8px;">
                        <div class="custom-control custom-checkbox mr-3">
                            <input type="checkbox" class="custom-control-input" id="chkConformidadCierre" checked required>
                            <label class="custom-control-label font-weight-bold text-dark" for="chkConformidadCierre" style="font-size:0.85rem; cursor:pointer;">
                                Constancia de Visita Técnica en Terreno:
                            </label>
                        </div>
                        <small class="text-muted" style="font-size:0.8rem;">
                            Ambas partes dejan constancia de que los datos relevados reflejan fielmente el estado actual de los servicios, recursos y cartera asistencial observada.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary font-weight-bold btn-sm px-3" data-dismiss="modal" style="border-radius:8px;">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success font-weight-bold btn-sm px-4 shadow-sm" id="btnConfirmarCierreFirmas" style="border-radius:8px;">
                    <i class="fa fa-file-signature mr-1"></i> Sellar y Cerrar Relevamiento
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ver Foto en Grande / Zoom --}}
<div class="modal fade" id="modalVerFotoGrande" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden; background:#0f172a;">
            <div class="modal-header py-2 px-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="text-white mb-0 font-weight-bold" id="modalFotoGrandeTitulo">Foto de Relevamiento</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 text-center" style="background:#000;">
                <img id="modalFotoGrandeImg" src="" alt="Foto Relevamiento" style="max-height:75vh; max-width:100%; object-fit:contain;">
            </div>
            <div class="modal-footer py-2 px-3 border-0 bg-dark text-white d-flex justify-content-between align-items-center">
                <p class="text-white-50 small mb-0" id="modalFotoGrandeDesc"></p>
                <button type="button" class="btn btn-sm btn-outline-light" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
const EST_ID = '{{ $est->id_establecimiento }}';
let evaluacionId = null;
let formulario   = null;
let respuestas   = {};
let checklistState = {};
let padResponsable = null;
let padsEvaluadores = [];

// ── Localidad Select2 encadenado ──────────────────────────────────────────────
function initLocalidadSelect(P) {
    var URL_DEPTOS  = '{{ route("riiss.localidades.departamentos") }}';
    var URL_DISTS   = '{{ route("riiss.localidades.distritos") }}';
    var URL_BARRIOS = '{{ route("riiss.localidades.barrios") }}';

    var S2 = {
        width: '100%', 
        allowClear: true, 
        dropdownParent: $('#contenidoFormulario'),
        language: {
            noResults:  function() { return 'Sin resultados'; },
            searching:  function() { return 'Buscando...'; },
        },
    };

    // 1. Inicializar los 3 campos inmediatamente
    $('#' + P + '-depto').select2($.extend({}, S2, {
        placeholder: 'Seleccioná departamento',
        ajax: {
            url: URL_DEPTOS, dataType: 'json', delay: 150,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; },
            cache: true,
        },
    }));

    $('#' + P + '-dist').select2($.extend({}, S2, {
        placeholder: 'Seleccioná ciudad/distrito',
        ajax: {
            url: URL_DISTS, dataType: 'json', delay: 150,
            data: function(p) { 
                return { q: p.term || '', cod_dpto: $('#' + P + '-depto').val() }; 
            },
            processResults: function(d) { return { results: d.results }; },
            cache: true,
        },
    }));

    $('#' + P + '-barrio').select2($.extend({}, S2, {
        placeholder: 'Seleccioná barrio/localidad',
        ajax: {
            url: URL_BARRIOS, dataType: 'json', delay: 150,
            data: function(p) { 
                return { q: p.term || '', cod_dpto: $('#' + P + '-depto').val(), cod_dist: $('#' + P + '-dist').val() }; 
            },
            processResults: function(d) { return { results: d.results }; },
            cache: true,
        },
    }));

    // Precargar departamentos para facilitar selección inicial
    $.get(URL_DEPTOS, { q: '' }, function(r) {
        if (!r.results) return;
        r.results.forEach(function(item) {
            if ($('#' + P + '-depto').find("option[value='" + item.id + "']").length === 0) {
                $('#' + P + '-depto').append(new Option(item.text, item.id, false, false));
            }
        });
        $('#' + P + '-depto').trigger('change.select2');
    });

    // 2. Manejar cascada (limpiar hijos cuando el padre cambia)
    $('#' + P + '-depto').on('change', function() {
        $('#' + P + '-dist').val(null).trigger('change');
        $('#' + P + '-barrio').val(null).trigger('change');
    });

    $('#' + P + '-dist').on('change', function() {
        $('#' + P + '-barrio').val(null).trigger('change');
    });

    // API pública para obtener valores
    window[P + 'GetLocalidad'] = function() {
        return {
            departamento: { 
                cod: $('#' + P + '-depto').val(), 
                text: $('#' + P + '-depto option:selected').text().trim() 
            },
            distrito: { 
                cod: $('#' + P + '-dist').val(),  
                text: $('#' + P + '-dist option:selected').text().trim() 
            },
            barrio: { 
                id:  $('#' + P + '-barrio').val(), 
                text: $('#' + P + '-barrio option:selected').text().trim() 
            },
        };
    };
}

$(document).ready(function() {
    // Inicializar localidad
    initLocalidadSelect('eval');

    // Autosave cuando cambia la ubicación
    $('#eval-depto, #eval-dist, #eval-barrio').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });

    // ── Select2 evaluadores ───────────────────────────────────────────────
    $('#evalEvaluadores').select2({
        placeholder: 'Buscar evaluador...', allowClear: true, multiple: true,
        minimumInputLength: 0, width: '100%',
        dropdownParent: $('#contenidoFormulario'),
        language: { noResults: function() { return 'Sin resultados'; }, searching: function() { return 'Buscando...'; } },
        ajax: {
            url: '{{ route("riiss.evaluaciones.usuarios") }}', dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; }, cache: true,
        },
    });

    // ── Select2 Plan PEI ───────────────────────────────────────────────
    $('#evalPeiProfile').select2({
        placeholder: '— Plan PEI 2024–2028 (Default) —', allowClear: true, width: '100%',
        dropdownParent: $('#contenidoFormulario'),
        ajax: {
            url: '{{ route("globales.get-pei-profiles") }}', dataType: 'json', delay: 250,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d }; }
        }
    });

    // Evitar que BMD robe el foco del input de búsqueda inline
    $(document).on('focusin', '.select2-selection--multiple', function(e) {
        e.stopImmediatePropagation();
    });
    $(document).on('mousedown', '.select2-selection--multiple', function(e) {
        e.stopImmediatePropagation();
    });
// Preseleccionar usuario actual
@if(auth()->check())
$('#evalEvaluadores').append(new Option('{{ addslashes(auth()->user()->name) }}', {{ auth()->id() }}, true, true)).trigger('change');
@endif

// ── Cargar o Crear evaluación ─────────────────────────────────────────────
const urlParams = new URLSearchParams(window.location.search);
evaluacionId = urlParams.get('evaluacion');

// Si no hay en URL, buscar en localStorage para este establecimiento
if (!evaluacionId) {
    evaluacionId = localStorage.getItem('riiss_eval_' + EST_ID);
}

if (evaluacionId) {
    recuperarEvaluacionExistente(evaluacionId);
} else {
    crearEvaluacionYCargar();
}

// Guardar datos de visita al cambiar (debounce)
    var saveTimer;

    // Aggressive Fix for Select2 search focus and interaction
    $(document).on('select2:opening', function(e) {
        // Stop theme scripts from capturing this opening event
        e.stopPropagation();
    });

    $(document).on('select2:open', function(e) {
        const dropdown = $('.select2-container--open');
        if (dropdown.length) {
            const searchField = dropdown.find('.select2-search__field');
            if (searchField.length) {
                // Remove any focus-stealing attributes
                searchField.attr('readonly', false);
                searchField.css('pointer-events', 'auto');
                
                setTimeout(() => {
                    searchField[0].focus();
                    searchField[0].setSelectionRange(0, 999); // Force selection on some mobile browsers
                }, 200);
            }
        }
    });

    $('#evalFecha, #evalTelefono, #estEmail, #estDireccion, #estRazonSocial, #estMapLink').on('change input', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
    $('#evalEvaluadores').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });
    $('#eval-depto, #eval-dist, #eval-barrio').on('change', function() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(actualizarDatosVisita, 800);
    });

    // ── Especialidades Médicas ───────────────────────────────────────────
    initSelectNuevaEspecialidad();
    cargarEspecialidadesEstablecimiento();
});

// ── Gestión de Especialidades Médicas del Establecimiento ────────────────────
function cargarEspecialidadesEstablecimiento() {
    $.get('/riiss/establecimientos/' + EST_ID + '/especialidades', function(r) {
        if (!r.ok) return;
        renderEspecialidadesPills(r.data);
        $('#badgeEspecialidadesCount').text(r.count);
    });
}

function renderEspecialidadesPills(lista) {
    var $c = $('#contenedorEspecialidadesPills');
    if (!lista || !lista.length) {
        $c.html('<div class="text-muted small font-italic py-2"><i class="fa fa-info-circle mr-1"></i>No hay especialidades registradas para este establecimiento. Haz clic en "Agregar Especialidad" para vincular una.</div>');
        return;
    }

    var html = '';
    lista.forEach(function(esp) {
        var nombreEscapado = (esp.nombre || '').replace(/'/g, "\\'");
        html += '<div class="badge badge-light border d-inline-flex align-items-center py-2 px-3 shadow-xs text-dark mr-1 mb-1" style="border-radius: 20px; font-size: 0.82rem; font-weight: 600; background: #f8fafc; border-color: #cbd5e1 !important; gap: 6px;">'
             + '<i class="fa fa-stethoscope text-primary mr-1" style="font-size:0.75rem;"></i>'
             + '<span>' + esp.nombre + '</span>'
             + '<button type="button" class="btn btn-link text-danger p-0 ml-1" onclick="eliminarEspecialidad(' + esp.id + ', \'' + nombreEscapado + '\')" title="Eliminar especialidad" style="line-height:1; font-size: 1.05rem; text-decoration:none; cursor:pointer;">'
             + '<i class="fa fa-times-circle"></i>'
             + '</button>'
             + '</div>';
    });
    $c.html(html);
}

function initSelectNuevaEspecialidad() {
    $('#selectNuevaEspecialidad').select2({
        placeholder: 'Buscar o escribir especialidad...',
        allowClear: true,
        width: '100%',
        tags: true,
        dropdownParent: $('#cardEspecialidadesEstablecimiento'),
        ajax: {
            url: '{{ route("riiss.especialidades.buscar") }}',
            dataType: 'json',
            delay: 200,
            data: function(p) { return { q: p.term || '' }; },
            processResults: function(d) { return { results: d.results }; },
            cache: true
        }
    });
}

function agregarEspecialidad() {
    var selData = $('#selectNuevaEspecialidad').select2('data');
    if (!selData || !selData.length) {
        mostrarToast('Seleccioná o escribí una especialidad', 'error');
        return;
    }

    var item = selData[0];
    var payload = {
        _token: '{{ csrf_token() }}'
    };

    if (item.id && !isNaN(item.id)) {
        payload.especialidad_id = item.id;
    } else {
        payload.nombre = item.text;
    }

    var $btn = $('#btnConfirmarAddEsp');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Vinculando...');

    $.ajax({
        url: '/riiss/establecimientos/' + EST_ID + '/especialidades',
        method: 'POST',
        data: payload,
        success: function(r) {
            $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Vincular Especialidad');
            if (r.ok) {
                renderEspecialidadesPills(r.data);
                $('#badgeEspecialidadesCount').text(r.count);
                $('#selectNuevaEspecialidad').val(null).trigger('change');
                $('#panelAgregarEspecialidad').slideUp(200);
                mostrarToast(r.message || 'Especialidad vinculada', 'success');
            } else {
                mostrarToast(r.message || 'Error al vincular especialidad', 'error');
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Vincular Especialidad');
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al vincular especialidad';
            mostrarToast(msg, 'error');
        }
    });
}

function eliminarEspecialidad(id, nombre) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Desvincular especialidad?',
            text: 'Se removerá "' + nombre + '" del establecimiento ' + EST_ID + '.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, desvincular',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                ejecutarEliminarEspecialidad(id);
            }
        });
    } else {
        if (confirm('¿Desvincular la especialidad "' + nombre + '"?')) {
            ejecutarEliminarEspecialidad(id);
        }
    }
}

function ejecutarEliminarEspecialidad(id) {
    $.ajax({
        url: '/riiss/establecimientos/' + EST_ID + '/especialidades/' + id,
        method: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) {
            if (r.ok) {
                renderEspecialidadesPills(r.data);
                $('#badgeEspecialidadesCount').text(r.count);
                mostrarToast(r.message || 'Especialidad eliminada', 'success');
            } else {
                mostrarToast(r.message || 'Error al eliminar', 'error');
            }
        },
        error: function() {
            mostrarToast('Error al desvincular especialidad', 'error');
        }
    });
}

// ── Recuperar evaluación por ID ──────────────────────────────────────────────
function recuperarEvaluacionExistente(id) {
    $('#evalEstado').html('<span class="badge badge-info">Cargando evaluación #' + id + '...</span>');
    $.get('/riiss/evaluaciones/' + id, function(r) {
        if (!r.ok) {
            mostrarToast('No se pudo cargar la evaluación', 'error');
            return;
        }
        const ev = r.data;
        evaluacionId = ev.id;
        $('#evalFecha').val(ev.fecha_evaluacion.split('T')[0]);
        $('#evalTelefono').val(ev.evaluador_telefono);

        // Restaurar campos adicionales desde metadata
        if (ev.metadata) {
            if (ev.metadata.email)        $('#estEmail').val(ev.metadata.email);
            if (ev.metadata.direccion)    $('#estDireccion').val(ev.metadata.direccion);
            if (ev.metadata.razon_social) $('#estRazonSocial').val(ev.metadata.razon_social);
            if (ev.metadata.map_link)     $('#estMapLink').val(ev.metadata.map_link);
        }
        // Cargar evaluadores
        if (ev.evaluadores && ev.evaluadores.length) {
            $('#evalEvaluadores').empty();
            ev.evaluadores.forEach(function(u) {
                if ($('#evalEvaluadores').find("option[value='" + u.id + "']").length === 0) {
                    $('#evalEvaluadores').append(new Option(u.text, u.id, true, true));
                } else {
                    $('#evalEvaluadores').val(u.id).trigger('change.select2');
                }
            });
            $('#evalEvaluadores').trigger('change');
        }

        // Cargar ubicación si existe en metadata
        if (ev.metadata && ev.metadata.ubicacion) {
            const u = ev.metadata.ubicacion;
            if (u.departamento && u.departamento.cod) {
                if ($('#eval-depto').find("option[value='" + u.departamento.cod + "']").length === 0) {
                    $('#eval-depto').append(new Option(u.departamento.text, u.departamento.cod, true, true)).trigger('change');
                } else {
                    $('#eval-depto').val(u.departamento.cod).trigger('change');
                }
                
                setTimeout(function() {
                    if (u.distrito && u.distrito.cod) {
                        if ($('#eval-dist').find("option[value='" + u.distrito.cod + "']").length === 0) {
                            $('#eval-dist').append(new Option(u.distrito.text, u.distrito.cod, true, true)).trigger('change');
                        } else {
                            $('#eval-dist').val(u.distrito.cod).trigger('change');
                        }
                        
                        setTimeout(function() {
                            if (u.barrio && u.barrio.id) {
                                if ($('#eval-barrio').find("option[value='" + u.barrio.id + "']").length === 0) {
                                    $('#eval-barrio').append(new Option(u.barrio.text, u.barrio.id, true, true)).trigger('change');
                                } else {
                                    $('#eval-barrio').val(u.barrio.id).trigger('change');
                                }
                            }
                        }, 600);
                    }
                }, 600);
            }
        }

        // Cargar respuestas ya guardadas
        if (ev.respuestas && ev.respuestas.length) {
            ev.respuestas.forEach(function(res) {
                respuestas[res.formulario_pregunta_id] = res.respuesta;
            });
        }

        // Renderizar fotos guardadas
        renderGaleriaFotos(ev.fotos || []);

        $('#evalEstado').html('<span class="badge badge-success">Evaluación #' + id + ' activa</span>');

        // Cargar formulario y DESPUÉS aplicar las respuestas visualmente
        cargarFormulario(function() {
            aplicarRespuestasVisuales(ev.respuestas || []);
            
            // Cargar observaciones y aspectos positivos guardados
            if (ev.observaciones_generales) {
                setRichEditorData('evalObservaciones', ev.observaciones_generales);
            }
            if (ev.aspectos_positivos) {
                setRichEditorData('evalAspectosPositivos', ev.aspectos_positivos);
            }
        });
    });
}

// ── Galería de Fotos del Relevamiento ───────────────────────────────────────
let _fotosRelevamiento = [];

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function renderGaleriaFotos(fotos) {
    _fotosRelevamiento = Array.isArray(fotos) ? fotos : [];
    const count = _fotosRelevamiento.length;
    $('#txtCantidadFotos').text(count);

    if (count >= 3) {
        $('#badgeContadorFotos').removeClass('border-warning text-warning text-dark').addClass('badge-success text-white').html('<i class="fa fa-check-circle mr-1"></i> ' + count + ' fotos cargadas (Completo)');
    } else {
        $('#badgeContadorFotos').removeClass('badge-success text-white').addClass('badge-light border text-dark').html('<i class="fa fa-images text-info mr-1"></i> ' + count + ' / 3 fotos recomendadas');
    }

    const $cont = $('#contenedorGaleriaFotos');
    $cont.empty();

    if (count === 0) {
        $cont.html(`
            <div class="col-12 text-center py-4">
                <div class="text-muted mb-2"><i class="fa fa-camera-retro fa-3x text-secondary" style="opacity:0.35;"></i></div>
                <h6 class="font-weight-bold text-dark mb-1">No hay fotografías cargadas aún</h6>
                <p class="text-muted small mb-3">Relevá y documentá visualmente el estado del establecimiento (mínimo 3 fotos recomendadas):</p>
                <div class="d-flex justify-content-center flex-wrap" style="gap:10px;">
                    <span class="badge badge-white border shadow-xs text-dark py-2 px-3"><i class="fa fa-hospital mr-1 text-primary"></i> 1. Fachada & Cartel</span>
                    <span class="badge badge-white border shadow-xs text-dark py-2 px-3"><i class="fa fa-stethoscope mr-1 text-success"></i> 2. Consultorios / Urgencias</span>
                    <span class="badge badge-white border shadow-xs text-dark py-2 px-3"><i class="fa fa-pills mr-1 text-info"></i> 3. Farmacia / Depósito</span>
                </div>
            </div>
        `);
        return;
    }

    _fotosRelevamiento.forEach(function(f, idx) {
        const descEsc = escapeHtml(f.descripcion || '');
        const idEsc = f.id || ('foto_' + idx);
        const cardHtml = `
            <div class="col-md-4 col-sm-6 mb-3" id="card-foto-${idEsc}">
                <div class="card h-100 border-0 shadow-sm" style="border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">
                    <div style="position:relative; height:180px; background:#0f172a; overflow:hidden; cursor:pointer;" onclick="ampliarFoto('${f.url}', '${escapeHtml(f.descripcion||'')}', 'Foto #${idx + 1}')">
                        <img src="${f.url}" alt="Foto ${idx + 1}" style="width:100%; height:100%; object-fit:cover; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <span class="badge badge-dark" style="position:absolute; top:8px; left:8px; background:rgba(15,23,42,0.8); font-size:0.75rem;">
                            <i class="fa fa-camera mr-1 text-info"></i>Foto #${idx + 1}
                        </span>
                        <span class="badge badge-info" style="position:absolute; top:8px; right:8px; background:rgba(2,132,199,0.9); font-size:0.7rem;">
                            <i class="fa fa-search-plus"></i>
                        </span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-between bg-white">
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark mb-1" style="font-size:0.75rem;"><i class="fa fa-align-left mr-1 text-info"></i>Descripción técnica:</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" id="desc-input-${idEsc}" value="${descEsc}" placeholder="Describir área o servicio...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="guardarDescripcionFoto('${idEsc}')" title="Guardar descripción">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto">
                            <small class="text-muted" style="font-size:0.7rem;"><i class="fa fa-clock mr-1"></i>${f.fecha || ''}</small>
                            <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2" onclick="eliminarFotoRelevamiento('${idEsc}')" style="font-size:0.72rem; border-radius:6px;" title="Eliminar foto">
                                <i class="fa fa-trash-alt mr-1"></i>Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $cont.append(cardHtml);
    });
}

function subirNuevaFoto(inputEl) {
    if (!inputEl.files || !inputEl.files[0]) return;
    const file = inputEl.files[0];
    const desc = prompt('📝 Ingresá una descripción para esta foto (ej: Fachada principal, Farmacia, Urgencias):', '') || '';
    enviarArchivoFoto(file, desc);
    inputEl.value = '';
}

function ejecutarSubidaFotoManual() {
    const fileInput = document.getElementById('inputArchivoFotoDirecta');
    if (!fileInput.files || !fileInput.files[0]) {
        mostrarToast('Seleccioná un archivo de imagen primero', 'warning');
        return;
    }
    const file = fileInput.files[0];
    const desc = $('#descNuevaFotoInput').val() || '';
    const btn = $('#btnSubirFotoManual');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');

    enviarArchivoFoto(file, desc, function() {
        btn.prop('disabled', false).html('<i class="fa fa-cloud-upload-alt mr-1"></i> Guardar Foto');
        $('#descNuevaFotoInput').val('');
        fileInput.value = '';
        $('#labelArchivoFoto').text('Seleccionar imagen...');
    }, function() {
        btn.prop('disabled', false).html('<i class="fa fa-cloud-upload-alt mr-1"></i> Guardar Foto');
    });
}

function enviarArchivoFoto(file, desc, callbackSuccess, callbackError) {
    if (!evaluacionId) {
        mostrarToast('Debes iniciar o guardar la evaluación primero', 'warning');
        if (typeof callbackError === 'function') callbackError();
        return;
    }

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('foto', file);
    formData.append('descripcion', desc);

    mostrarToast('Subiendo fotografía...', 'info');

    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/fotos',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(r) {
            if (r.ok) {
                mostrarToast('Fotografía agregada a la evidencia técnica ✅', 'success');
                renderGaleriaFotos(r.fotos || []);
                if (typeof callbackSuccess === 'function') callbackSuccess();
            } else {
                mostrarToast(r.message || 'Error al subir foto', 'error');
                if (typeof callbackError === 'function') callbackError();
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al subir archivo de imagen';
            mostrarToast(msg, 'error');
            if (typeof callbackError === 'function') callbackError();
        }
    });
}

function guardarDescripcionFoto(fotoId) {
    if (!evaluacionId) return;
    const desc = $('#desc-input-' + fotoId).val() || '';
    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/fotos/' + fotoId + '/descripcion',
        method: 'PATCH',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: '{{ csrf_token() }}',
            descripcion: desc
        }),
        success: function(r) {
            if (r.ok) {
                mostrarToast('Descripción guardada ✅', 'success');
                if (r.fotos) renderGaleriaFotos(r.fotos);
            }
        }
    });
}

function eliminarFotoRelevamiento(fotoId) {
    if (!evaluacionId) return;
    if (!confirm('¿Estás seguro de eliminar esta fotografía de la evidencia técnica?')) return;

    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/fotos/' + fotoId,
        method: 'DELETE',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) {
            if (r.ok) {
                mostrarToast('Fotografía eliminada ✅', 'success');
                renderGaleriaFotos(r.fotos || []);
            }
        }
    });
}

function ampliarFoto(url, desc, title) {
    $('#modalFotoGrandeImg').attr('src', url);
    $('#modalFotoGrandeTitulo').text(title || 'Evidencia Fotográfica');
    $('#modalFotoGrandeDesc').text(desc || 'Sin descripción');
    $('#modalVerFotoGrande').modal('show');
}

// ── Helpers para Texto Enriquecido (CKEditor) ──────────────────────────────
function initRichTextEditors() {
    if (typeof CKEDITOR === 'undefined') return;

    try {
        var ckConfig = {
            height: 150,
            toolbarGroups: [
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
                { name: 'links' },
                { name: 'styles' },
                { name: 'colors' }
            ],
            removeButtons: 'Underline,Subscript,Superscript,Strike,Styles'
        };

        ['evalAspectosPositivos', 'evalObservaciones'].forEach(function(fieldId) {
            if ($('#' + fieldId).length) {
                if (CKEDITOR.instances && CKEDITOR.instances[fieldId]) {
                    try { CKEDITOR.instances[fieldId].destroy(true); } catch(e){}
                }
                try {
                    CKEDITOR.replace(fieldId, ckConfig);
                } catch(e) {
                    console.warn('CKEditor replace warning on ' + fieldId, e);
                }
            }
        });
    } catch(err) {
        console.warn('CKEditor init warning:', err);
    }
}

function getRichEditorData(fieldId) {
    try {
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances[fieldId]) {
            return CKEDITOR.instances[fieldId].getData();
        }
    } catch(e) {
        console.warn('CKEditor getData warning on ' + fieldId, e);
    }
    return $('#' + fieldId).val() || '';
}

function setRichEditorData(fieldId, content) {
    var safeContent = content || '';
    try {
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances[fieldId]) {
            if (CKEDITOR.instances[fieldId].status === 'ready') {
                CKEDITOR.instances[fieldId].setData(safeContent);
            } else {
                CKEDITOR.instances[fieldId].on('instanceReady', function() {
                    this.setData(safeContent);
                });
            }
            return;
        }
    } catch(e) {
        console.warn('CKEditor setData warning on ' + fieldId, e);
    }
    $('#' + fieldId).val(safeContent);
}

// ── Crear evaluación al cargar la página ─────────────────────────────────────
function crearEvaluacionYCargar() {
    $('#evalEstado').html('<span class="badge badge-warning">Creando...</span>');

    $.ajax({
        url: '{{ route("riiss.evaluaciones.crear") }}',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            _token:             '{{ csrf_token() }}',
            id_establecimiento: EST_ID,
            fecha_evaluacion:   $('#evalFecha').val(),
            evaluadores:        [{ id: {{ auth()->id() ?? 0 }}, text: '{{ addslashes(auth()->user()?->name ?? "") }}' }],
            pei_profile_id:     $('#evalPeiProfile').val() || null,
        }),
        success: function(r) {
            if (!r.ok) return;
            evaluacionId = r.data.id;

            // Persistir en URL y localStorage para sobrevivir refresh
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?evaluacion=' + evaluacionId;
            window.history.replaceState({path:newUrl}, '', newUrl);
            localStorage.setItem('riiss_eval_' + EST_ID, evaluacionId);

            $('#evalEstado').html('<span class="badge badge-success">Evaluación #' + evaluacionId + ' activa</span>');
            renderGaleriaFotos(r.data.fotos || []);
            cargarFormulario();

            // Enviar respuestas que se marcaron antes de que se creara la evaluación
            setTimeout(enviarColaRespuestas, 1000);
        },
        error: function(xhr) {
            $('#evalEstado').html('<span class="badge badge-danger">Error al crear</span>');
            console.error(xhr.responseJSON);
        }
    });
}

// ── Actualizar datos de visita en background ──────────────────────────────────
function actualizarDatosVisita() {
    if (!evaluacionId) return;
    const evalData   = $('#evalEvaluadores').select2('data');
    const evaluadores = evalData.map(function(e) { return { id: e.id, text: e.text }; });

    const ubicacion = {
        departamento: { cod: $('#eval-depto').val(), text: $('#eval-depto option:selected').text() },
        distrito:     { cod: $('#eval-dist').val(),  text: $('#eval-dist option:selected').text() },
        barrio:       { id:  $('#eval-barrio').val(), text: $('#eval-barrio option:selected').text() },
    };

    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/datos-visita',
        method: 'PATCH',
        contentType: 'application/json',
        data: JSON.stringify({
            _token:             '{{ csrf_token() }}',
            fecha_evaluacion:   $('#evalFecha').val(),
            evaluadores:        evaluadores,
            evaluador_telefono: $('#evalTelefono').val(),
            metadata: {
                ubicacion:    ubicacion,
                email:        $('#estEmail').val(),
                direccion:    $('#estDireccion').val(),
                razon_social: $('#estRazonSocial').val(),
                map_link:     $('#estMapLink').val(),
            },
        }),
        success: function() { mostrarToast('Datos guardados', 'success'); },
    });
}

function guardarObservaciones() {
    if (!evaluacionId) return;
    var btn = $('#btnGuardarObs');
    btn.html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');
    
    var obs = getRichEditorData('evalObservaciones');
    var asp = getRichEditorData('evalAspectosPositivos');
    
    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/datos-visita',
        method: 'PATCH',
        contentType: 'application/json',
        data: JSON.stringify({
            _token: '{{ csrf_token() }}',
            observaciones_generales: obs,
            aspectos_positivos: asp
        }),
        success: function() { 
            btn.html('<i class="fa fa-save mr-1"></i>Guardar Observaciones y Aspectos Positivos');
            mostrarToast('Observaciones y aspectos positivos guardados con éxito', 'success'); 
        },
        error: function() {
            btn.html('<i class="fa fa-save mr-1"></i>Guardar Observaciones y Aspectos Positivos');
            mostrarToast('Error al guardar observaciones', 'error');
        }
    });
}

// Autoguardado al salir de la página
window.addEventListener('beforeunload', function() {
    if (evaluacionId && ($('#evalObservaciones').length || $('#evalAspectosPositivos').length)) {
        var obs = getRichEditorData('evalObservaciones');
        var asp = getRichEditorData('evalAspectosPositivos');
        navigator.sendBeacon('/riiss/evaluaciones/' + evaluacionId + '/datos-visita', new Blob([JSON.stringify({
            _token: '{{ csrf_token() }}',
            _method: 'PATCH',
            observaciones_generales: obs,
            aspectos_positivos: asp
        })], {type: 'application/json'}));
    }
});

// ── Cargar formulario ─────────────────────────────────────────────────────────
function cargarFormulario(callback) {
    $('#listaSecciones').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>');
    $('#seccionesFormulario').html('<div class="text-center py-5"><div class="spinner-border text-danger"></div><p class="text-muted mt-2">Cargando formulario...</p></div>');

    $.get('/riiss/evaluaciones/formulario/' + EST_ID, function(r) {
        if (!r.ok) return;
        formulario = r.data;
        renderSidebar(formulario.secciones);
        renderSecciones(formulario.secciones);
        $('#botonesAccion').show();
        $('#panelObservaciones').show();
        $('#panelAspectosPositivos').show();
        $('#panelFotosRelevamiento').show();
        actualizarProgreso();
        initBuscadorPreguntas();

        // Inicializar CKEditor para observaciones y aspectos positivos
        initRichTextEditors();

        // Ejecutar callback después de renderizar (para aplicar respuestas guardadas)
        if (typeof callback === 'function') {
            setTimeout(callback, 200);
        }
    });
}

// ── Aplicar respuestas guardadas visualmente ─────────────────────────────────
function aplicarRespuestasVisuales(listaRespuestas) {
    if (!listaRespuestas || !listaRespuestas.length) return;

    listaRespuestas.forEach(function(res) {
        var pid  = res.formulario_pregunta_id;
        var val  = (res.respuesta || '').toString().toLowerCase().trim();
        var $preg = $('#preg-' + pid);
        if (!$preg.length) return;

        var $btns = $preg.find('.resp-btn');
        if ($btns.length) {
            // Botones Sí/No/NA
            $btns.each(function() {
                var txt = $(this).text().toLowerCase();
                if (val === 'si' || val === 'sí') {
                    if (txt.indexOf('sí') >= 0 || txt.indexOf('si') >= 0) {
                        $(this).addClass('selected-si');
                    }
                } else if (val === 'no aplica') {
                    if (txt.indexOf('no aplica') >= 0) {
                        $(this).addClass('selected-na');
                    }
                } else if (val === 'no') {
                    if (txt.indexOf('no') >= 0 && txt.indexOf('aplica') < 0) {
                        $(this).addClass('selected-no');
                    }
                } else {
                    // Checklist
                    if (txt.indexOf(val) >= 0) {
                        $(this).addClass('selected-si');
                    }
                }
            });
        } else {
            // Input de texto/número
            var $input = $preg.find('input[type="text"], input[type="number"], textarea');
            if ($input.length) {
                $input.val(res.respuesta);
            }
        }

        // Actualizar objeto respuestas y sidebar
        respuestas[pid] = res.respuesta;
        actualizarSidebarSeccion(pid);
    });

    actualizarProgreso();
}

// ── Sidebar ───────────────────────────────────────────────────────────────────
function renderSidebar(secciones) {
    var html = '';
    secciones.forEach(function(s) {
        html += '<button class="sec-item" id="side-' + s.id + '" onclick="irSeccion(' + s.id + ')">'
            + '<span class="sec-dot"></span>'
            + '<span class="flex-grow-1">' + s.seccion + '</span>'
            + '<small class="text-muted">' + s.preguntas.length + '</small>'
            + '</button>';
    });
    $('#listaSecciones').html(html);
}

// ── Secciones ─────────────────────────────────────────────────────────────────
function renderSecciones(secciones) {
    var html = '';
    secciones.forEach(function(s) {
        var badge = s.requerida
            ? '<span class="badge badge-danger ml-auto">Requerida</span>'
            : '<span class="badge badge-secondary ml-auto">Opcional</span>';
        var sub = s.sub_seccion ? '<br><small class="text-muted">' + s.sub_seccion + '</small>' : '';
        html += '<div class="seccion-card" id="sec-' + s.id + '">'
            + '<div class="seccion-header"><i class="fa fa-folder-open text-danger"></i>'
            + '<div><strong>' + s.seccion + '</strong>' + sub + '</div>' + badge + '</div>'
            + '<div class="seccion-body">' + s.preguntas.map(renderPregunta).join('') + '</div>'
            + '</div>';
    });
    $('#seccionesFormulario').html(html);
}

// ── Pregunta ──────────────────────────────────────────────────────────────────
function renderPregunta(p) {
    var req = p.requerida ? '<span class="req-star">*</span>' : '';
    var vinculo = p.servicio_cartera_grupo
        ? '<small class="text-muted ml-2"><i class="fa fa-link"></i> ' + p.servicio_cartera_grupo + '</small>'
        : '';
    var input = '';

    if (p.tipo_respuesta === 'si_no') {
        input = '<div class="resp-group">'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'Si\',this,\'si\')">✅ Sí</button>'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'No\',this,\'no\')">❌ No</button>'
            + '</div>';
    } else if (p.tipo_respuesta === 'si_no_na') {
        input = '<div class="resp-group">'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'Si\',this,\'si\')">✅ Sí</button>'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'No\',this,\'no\')">❌ No</button>'
            + '<button class="resp-btn" onclick="setResp(' + p.id + ',\'No Aplica\',this,\'na\')">⬜ No Aplica</button>'
            + '</div>';
    } else if (p.tipo_respuesta === 'checklist' && p.opciones) {
        input = '<div class="resp-group">'
            + p.opciones.map(function(op) {
                return '<button class="resp-btn" onclick="toggleChecklist(' + p.id + ',\'' + op + '\',this)">' + op + '</button>';
            }).join('')
            + '</div>';
    } else if (p.tipo_respuesta === 'numero') {
        input = '<input type="number" class="form-control form-control-sm" style="max-width:120px" onchange="setRespTexto(' + p.id + ',this.value)" placeholder="0">';
    } else {
        var pre = p.valor_prellenado ? 'value="' + p.valor_prellenado + '"' : '';
        input = '<input type="text" class="form-control form-control-sm" ' + pre
            + ' onchange="setRespTexto(' + p.id + ',this.value)"'
            + ' placeholder="' + (p.respuesta_ejemplo || 'Ingrese respuesta...') + '">';
        if (p.valor_prellenado) {
            setTimeout(function() { setRespTexto(p.id, p.valor_prellenado); }, 100);
        }
    }

    return '<div class="pregunta-item" id="preg-' + p.id + '">'
        + '<div class="pregunta-label">' + req + ' ' + p.pregunta + vinculo + '</div>'
        + input + '</div>';
}

// ── Respuestas ────────────────────────────────────────────────────────────────
var autosaveTimers = {};
var colaRespuestas = {}; // respuestas pendientes mientras evaluacionId es null

function autosaveRespuesta(id, valor) {
    // Si aún no hay evaluación, encolar
    if (!evaluacionId) {
        colaRespuestas[id] = valor;
        return;
    }
    clearTimeout(autosaveTimers[id]);
    autosaveTimers[id] = setTimeout(function() {
        $.ajax({
            url: '/riiss/evaluaciones/' + evaluacionId + '/respuestas',
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({
                _token: '{{ csrf_token() }}',
                respuestas: [{ formulario_pregunta_id: parseInt(id), respuesta: valor }]
            }),
            success: function() {
                $('#preg-' + id).find('.autosave-indicator').remove();
                $('#preg-' + id).append('<span class="autosave-indicator text-success" style="font-size:.7rem;margin-left:8px"><i class="fa fa-check"></i></span>');
                setTimeout(function() { $('#preg-' + id).find('.autosave-indicator').fadeOut(500, function(){ $(this).remove(); }); }, 2000);
            }
        });
    }, 500);
}

// Enviar respuestas encoladas cuando se crea la evaluación
function enviarColaRespuestas() {
    var ids = Object.keys(colaRespuestas);
    if (!ids.length || !evaluacionId) return;
    var payload = ids.map(function(id) {
        return { formulario_pregunta_id: parseInt(id), respuesta: colaRespuestas[id] };
    });
    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/respuestas',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({ _token: '{{ csrf_token() }}', respuestas: payload }),
        success: function() {
            colaRespuestas = {};
        }
    });
}

function setResp(id, valor, btn, tipo) {
    respuestas[id] = valor;
    $(btn).closest('.resp-group').find('.resp-btn').removeClass('selected-si selected-no selected-na');
    $(btn).addClass('selected-' + tipo);
    actualizarProgreso();
    actualizarSidebarSeccion(id);
    autosaveRespuesta(id, valor);
}

function setRespTexto(id, valor) {
    if (valor && valor.toString().trim()) {
        respuestas[id] = valor.toString().trim();
        actualizarProgreso();
        actualizarSidebarSeccion(id);
        autosaveRespuesta(id, valor);
    }
}

function toggleChecklist(id, opcion, btn) {
    if (!checklistState[id]) checklistState[id] = [];
    var idx = checklistState[id].indexOf(opcion);
    if (idx >= 0) { checklistState[id].splice(idx, 1); $(btn).removeClass('selected-si'); }
    else          { checklistState[id].push(opcion);   $(btn).addClass('selected-si'); }
    respuestas[id] = checklistState[id].join(', ');
    actualizarProgreso();
    autosaveRespuesta(id, respuestas[id]);
}

// ── Progreso ──────────────────────────────────────────────────────────────────
function actualizarProgreso() {
    if (!formulario) return;
    var total = formulario.resumen.total_preguntas;
    var respondidas = Object.keys(respuestas).length;
    var pct = total > 0 ? Math.round((respondidas / total) * 100) : 0;
    $('#barraFill').css('width', pct + '%');
    $('#txtProgreso').text(pct + '% completado');
    $('#txtRespuestas').text(respondidas + ' de ' + total + ' respuestas');
}

function actualizarSidebarSeccion(preguntaId) {
    if (!formulario) return;
    formulario.secciones.forEach(function(s) {
        var ids = s.preguntas.map(function(p) { return p.id; });
        if (ids.indexOf(preguntaId) < 0) return;
        var resp = ids.filter(function(id) { return respuestas[id]; }).length;
        var $dot = $('#side-' + s.id).find('.sec-dot').removeClass('completa parcial');
        if (resp === ids.length) $dot.addClass('completa');
        else if (resp > 0)       $dot.addClass('parcial');
    });
}

// ── Navegación ────────────────────────────────────────────────────────────────
function irSeccion(id) {
    var el = document.getElementById('sec-' + id);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    $('.sec-item').removeClass('active');
    $('#side-' + id).addClass('active');
}

$(window).on('scroll', function() {
    if (!formulario) return;
    formulario.secciones.forEach(function(s) {
        var el = document.getElementById('sec-' + s.id);
        if (!el) return;
        var rect = el.getBoundingClientRect();
        if (rect.top <= 120 && rect.bottom >= 120) {
            $('.sec-item').removeClass('active');
            $('#side-' + s.id).addClass('active');
        }
    });
});

// ── Gestión de Firmas y Cierre Formal ─────────────────────────────────────────
function renderModalFirmasEvaluadores() {
    let evalData = $('#evalEvaluadores').select2('data');
    if (!evalData || evalData.length === 0) {
        evalData = [{
            id: {{ auth()->id() ?? 'null' }},
            text: '{{ addslashes(auth()->user() ? auth()->user()->name : "Evaluador IPS") }}'
        }];
    }

    padsEvaluadores = [];
    let html = '';
    evalData.forEach((ev, idx) => {
        let nombre = ev.text || 'Evaluador ' + (idx + 1);
        let uid = ev.id || '';
        html += `
        <div class="border rounded p-3 mb-3 evaluador-firma-box" data-idx="${idx}" data-userid="${uid}" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info text-white font-weight-bold mr-2 d-flex align-items-center justify-content-center" style="width:24px; height:24px; font-size:0.75rem;">
                        ${idx + 1}
                    </div>
                    <div>
                        <strong class="text-dark d-block" style="font-size:0.88rem;">${nombre}</strong>
                        <small class="text-muted" style="font-size:0.72rem;">Técnico Evaluador IPS</small>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2 btnClearFirmaEval" data-idx="${idx}" style="font-size:0.72rem; border-radius:6px;">
                    <i class="fa fa-eraser mr-1"></i>Limpiar
                </button>
            </div>
            <input type="hidden" class="eval-nombre-input" value="${nombre}">
            <div class="form-group mb-2">
                <label class="font-weight-bold text-dark small mb-1" style="font-size:0.74rem;">Cargo / Dependencia</label>
                <input type="text" class="form-control form-control-sm eval-cargo-input" value="Evaluador / Analista RIISS — Dirección de Planificación" placeholder="Cargo">
            </div>
            <div class="form-group mb-0">
                <label class="font-weight-bold text-dark small mb-1" style="font-size:0.74rem;"><i class="fa fa-pen-alt mr-1 text-info"></i>Firma Digital del Técnico <span class="text-danger">*</span></label>
                <div class="border rounded bg-white d-flex align-items-center justify-content-center p-1" style="border: 2px dashed #94a3b8 !important; border-radius: 8px;">
                    <canvas id="canvasFirmaEvaluador_${idx}" width="460" height="130" style="touch-action: none; width: 100%; height: 130px; background: #ffffff; border-radius: 6px; cursor: crosshair;"></canvas>
                </div>
                <small class="text-muted d-block mt-1" style="font-size:0.68rem;"><i class="fa fa-info-circle mr-1"></i>Rúbrica de conformidad técnica.</small>
            </div>
        </div>
        `;
    });

    $('#contenedorFirmasEvaluadoresModal').html(html);
    $('#txtResumenEvaluadoresModal').text(evalData.length + ' evaluador(es) comisionado(s)');
}

function initSignaturePadsCierre() {
    var canvasResp = document.getElementById('canvasFirmaResponsable');

    if (canvasResp) {
        if (!padResponsable) {
            padResponsable = new SignaturePad(canvasResp, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(15, 23, 42)'
            });
        }
        var r1 = Math.max(window.devicePixelRatio || 1, 1);
        canvasResp.width = canvasResp.offsetWidth * r1;
        canvasResp.height = canvasResp.offsetHeight * r1;
        canvasResp.getContext("2d").scale(r1, r1);
        padResponsable.clear();
    }

    // Inicializar pads para cada evaluador
    padsEvaluadores = [];
    $('.evaluador-firma-box').each(function() {
        var idx = $(this).data('idx');
        var canvas = document.getElementById('canvasFirmaEvaluador_' + idx);
        if (canvas) {
            var pad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(15, 23, 42)'
            });
            var r = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * r;
            canvas.height = canvas.offsetHeight * r;
            canvas.getContext("2d").scale(r, r);
            pad.clear();
            padsEvaluadores[idx] = pad;
        }
    });
}

function abrirModalCierreFirmas() {
    if (!evaluacionId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Evaluación no inicializada',
                text: 'Esperá que se inicialice la evaluación antes de firmar.',
                confirmButtonColor: '#1a237e',
                confirmButtonText: 'Entendido'
            });
        } else {
            mostrarToast('Esperá que se inicialice la evaluación antes de firmar.', 'error');
        }
        return;
    }

    var total = formulario ? (formulario.resumen ? formulario.resumen.total_preguntas : 0) : 0;
    var respondidas = Object.keys(respuestas).length;
    
    function ejecutarAperturaModal() {
        // Guardar respuestas pendientes primero
        if (Object.keys(respuestas).length) {
            enviarRespuestas(false);
        }

        renderModalFirmasEvaluadores();
        $('#modalCierreFirmas').modal('show');
        setTimeout(function() {
            initSignaturePadsCierre();
        }, 300);
    }

    if (total > 0 && respondidas < total * 0.4) {
        var pct = Math.round((respondidas / total) * 100);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Proceder al Cierre y Firmas?',
                html: `
                    <div class="text-left p-3" style="background:#fff8e1; border-radius:8px; border-left:4px solid #ffb300;">
                        <p class="mb-2 text-dark" style="font-size:1rem;">
                            Solo has respondido <strong>${respondidas}</strong> de <strong>${total}</strong> preguntas (<strong>${pct}%</strong>).
                        </p>
                        <p class="mb-0 text-muted small">
                            <i class="fa fa-info-circle text-warning mr-1"></i> ¿Deseas proceder con el cierre y firmas en terreno de todas formas?
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1a237e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-file-signature mr-1"></i> Sí, proceder al Acta de Cierre',
                cancelButtonText: 'Continuar respondiendo',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarAperturaModal();
                }
            });
            return;
        } else {
            if (!confirm('Solo has respondido ' + respondidas + ' de ' + total + ' preguntas (' + pct + '%). ¿Deseas proceder con el cierre y firmas en terreno de todas formas?')) {
                return;
            }
        }
    }

    ejecutarAperturaModal();
}

$(document).ready(function() {
    $('#btnClearFirmaResponsable').on('click', function() {
        if (padResponsable) padResponsable.clear();
    });

    $(document).on('click', '.btnClearFirmaEval', function() {
        var idx = $(this).data('idx');
        if (padsEvaluadores[idx]) {
            padsEvaluadores[idx].clear();
        }
    });

    $('#btnConfirmarCierreFirmas').on('click', function(e) {
        e.preventDefault();

        var nombreResp = $('#cierre_responsable_nombre').val().trim();
        var cargoResp  = $('#cierre_responsable_cargo').val().trim();

        if (!nombreResp) {
            mostrarToast('Por favor, ingresá el nombre del responsable del establecimiento.', 'error');
            $('#cierre_responsable_nombre').focus();
            return;
        }

        if (!cargoResp) {
            mostrarToast('Por favor, ingresá o seleccioná el cargo del responsable receptor.', 'error');
            $('#cierre_responsable_cargo').focus();
            return;
        }

        if (!padResponsable || padResponsable.isEmpty()) {
            mostrarToast('La firma digital del responsable del establecimiento es obligatoria.', 'error');
            return;
        }

        var evaluadoresFirmas = [];
        var hayAlgunaFirma = false;
        $('.evaluador-firma-box').each(function() {
            var idx = $(this).data('idx');
            var userId = $(this).data('userid');
            var nombre = $(this).find('.eval-nombre-input').val().trim();
            var cargo  = $(this).find('.eval-cargo-input').val().trim();
            var pad    = padsEvaluadores[idx];

            if (pad && !pad.isEmpty()) {
                hayAlgunaFirma = true;
                evaluadoresFirmas.push({
                    user_id: userId ? parseInt(userId) : null,
                    nombre:  nombre,
                    cargo:   cargo,
                    firma:   pad.toDataURL('image/png')
                });
            }
        });

        if (!hayAlgunaFirma) {
            mostrarToast('Debe estampar la firma digital de al menos un técnico evaluador IPS.', 'error');
            return;
        }

        if (!$('#chkConformidadCierre').is(':checked')) {
            mostrarToast('Debés marcar la casilla de constancia y conformidad.', 'error');
            return;
        }

        var btn = $('#btnConfirmarCierreFirmas');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Sellando firmas y cerrando...');

        var firmaRespBase64 = padResponsable.toDataURL('image/png');
        var primeraFirma = evaluadoresFirmas[0];

        var payload = {
            _token: '{{ csrf_token() }}',
            responsable_nombre:    nombreResp,
            responsable_cargo:     cargoResp,
            responsable_documento: $('#cierre_responsable_documento').val().trim(),
            responsable_telefono:  $('#cierre_responsable_telefono').val().trim(),
            responsable_firma:     firmaRespBase64,
            evaluadores_firmas:    evaluadoresFirmas,
            evaluador_nombre:      primeraFirma ? primeraFirma.nombre : '',
            evaluador_cargo:       primeraFirma ? primeraFirma.cargo : '',
            evaluador_firma:       primeraFirma ? primeraFirma.firma : '',
            cierre_observaciones:  $('#cierre_observaciones_cierre').val().trim(),
        };

        $.ajax({
            url: '/riiss/evaluaciones/' + evaluacionId + '/cerrar-con-firmas',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(resp) {
                btn.prop('disabled', false).html('<i class="fa fa-file-signature mr-1"></i> Sellar y Cerrar Relevamiento');
                $('#modalCierreFirmas').modal('hide');
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¡Acta de Cierre Registrada!',
                        html: `
                            <div class="text-center p-2">
                                <p class="mb-2 text-dark font-weight-bold">El relevamiento en terreno ha sido cerrado y rubricado formalmente.</p>
                                <p class="mb-0 text-muted small">Firmas de conformidad registradas para <strong>${nombreResp}</strong> y el equipo evaluador IPS.</p>
                            </div>
                        `,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#1a237e',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa fa-file-alt mr-1"></i> Ver Acta & Reporte',
                        cancelButtonText: 'Permanecer aquí'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/riiss/evaluaciones/' + evaluacionId;
                        }
                    });
                } else {
                    mostrarToast(resp.message || 'Relevamiento cerrado con éxito', 'success');
                }

                // Ejecutar análisis de brechas final y mostrar resultado
                ejecutarAnalisis();
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-file-signature mr-1"></i> Sellar y Cerrar Relevamiento');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error al sellar las firmas.';
                mostrarToast(msg, 'error');
            }
        });
    });

    $(document).on('change', '#inputArchivoFotoDirecta', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#labelArchivoFoto').text(fileName || 'Seleccionar imagen...');
    });
});

// ── Guardar / Finalizar ───────────────────────────────────────────────────────
function guardarParcial() {
    if (!evaluacionId || !Object.keys(respuestas).length) {
        mostrarToast('No hay respuestas para guardar', 'error'); return;
    }
    enviarRespuestas(false);
}

function finalizarEvaluacion() {
    abrirModalCierreFirmas();
}

function enviarRespuestas(ejecutarGap) {
    var payload = Object.keys(respuestas).map(function(id) {
        return { formulario_pregunta_id: parseInt(id), respuesta: respuestas[id] };
    });

    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/respuestas',
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify({ _token: '{{ csrf_token() }}', respuestas: payload }),
        success: function(r) {
            if (!r.ok) return;
            if (ejecutarGap) ejecutarAnalisis();
            else mostrarToast('Respuestas guardadas', 'success');
        },
        error: function(xhr) {
            mostrarToast('Error al guardar: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.status), 'error');
        }
    });
}

// ── Gap Analysis ──────────────────────────────────────────────────────────────
function ejecutarAnalisis() {
    $('#botonesAccion').html('<div class="card-body text-center py-4"><div class="spinner-border text-danger mb-2"></div><p class="text-muted">Analizando brechas con la cartera de servicios...</p></div>');
    $.ajax({
        url: '/riiss/evaluaciones/' + evaluacionId + '/ejecutar-gap',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(r) { if (r.ok) mostrarResultado(r.data); },
        error: function() { mostrarToast('Error al ejecutar el análisis', 'error'); }
    });
}

function mostrarResultado(data) {
    // Limpiar localStorage — evaluación completada
    localStorage.removeItem('riiss_eval_' + EST_ID);
    var res    = data.cartera.resumen;
    var clasif = data.clasificacion_final;
    var pct    = data.cartera.porcentaje;
    var iconos = { CUMPLE: '✅', CUMPLE_PARCIALMENTE: '⚠️', NO_CUMPLE: '❌' };
    var labels = { CUMPLE: 'CUMPLE', CUMPLE_PARCIALMENTE: 'CUMPLE PARCIALMENTE', NO_CUMPLE: 'NO CUMPLE' };

    const ahora = new Date();
    const fechaStr = ahora.toLocaleDateString('es-PY', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    const horaStr  = ahora.toLocaleTimeString('es-PY', { hour: '2-digit', minute: '2-digit' });

    var acciones = '';
    if (data.cartera.acciones_criticas && data.cartera.acciones_criticas.length) {
        acciones = '<div class="mt-4 text-left"><h6 class="font-weight-bold"><i class="fa fa-exclamation-triangle text-danger mr-2"></i>Acciones críticas</h6><ul class="list-unstyled">'
            + data.cartera.acciones_criticas.slice(0,5).map(function(a) {
                return '<li class="mb-2 p-2 bg-white rounded" style="border-left:3px solid #ef4444"><strong>' + a.servicio + '</strong><small class="d-block text-muted">' + (a.accion_recomendada || '') + '</small></li>';
            }).join('') + '</ul></div>';
    }

    var html = '<div class="resultado-card resultado-' + clasif + '">'
        + '<div class="mb-2"><span class="badge badge-dark">Finalizado: ' + fechaStr + ' a las ' + horaStr + '</span></div>'
        + '<div style="font-size:3rem">' + (iconos[clasif] || '❓') + '</div>'
        + '<h3 class="font-weight-bold mt-2">' + (labels[clasif] || clasif) + '</h3>'
        + '<div style="font-size:2.5rem;font-weight:800">' + pct + '%</div>'
        + '<p class="text-muted">de cumplimiento con la cartera de servicios</p>'
        + '<div class="row mt-3 text-center">'
        + '<div class="col-3"><div class="h4 font-weight-bold text-success">' + res.cumple + '</div><small>Cumplen</small></div>'
        + '<div class="col-3"><div class="h4 font-weight-bold text-danger">' + res.no_cumple + '</div><small>No cumplen</small></div>'
        + '<div class="col-3"><div class="h4 font-weight-bold text-warning">' + res.no_verificable + '</div><small>No verificables</small></div>'
        + '<div class="col-3"><div class="h4 font-weight-bold text-muted">' + res.pendiente + '</div><small>Pendientes</small></div>'
        + '</div>' + acciones
        + '<div class="mt-4">'
        + '<a href="/riiss/evaluaciones/' + evaluacionId + '" class="btn btn-dark mr-2"><i class="fa fa-chart-bar mr-1"></i>Ver detalle</a>'
        + '<a href="{{ route("riiss.index") }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left mr-1"></i>Volver</a>'
        + '</div></div>';

    $('#panelResultado').html(html).show();
    $('#botonesAccion').hide();
    $('html, body').animate({ scrollTop: $('#panelResultado').offset().top - 80 }, 600);
}

// ── Buscador de preguntas con Select2 ────────────────────────────────────
function initBuscadorPreguntas() {
    if (!formulario) return;

    var datos = [];
    formulario.secciones.forEach(function(s) {
        s.preguntas.forEach(function(p) {
            datos.push({ id: p.id, text: p.pregunta, seccion: s.seccion });
        });
    });

    $('#buscadorPreguntas').select2({
        placeholder: '🔍 Buscar pregunta...',
        allowClear: true,
        width: '100%',
        data: datos,
        dropdownParent: $('#buscadorPreguntas').parent(),
        templateResult: function(item) {
            if (!item.id) return item.text;
            return $('<div><div style="font-size:.82rem;line-height:1.3">' + item.text + '</div><small style="color:#9ca3af">' + item.seccion + '</small></div>');
        },
        language: {
            noResults:  function() { return 'Sin resultados'; },
            searching:  function() { return 'Buscando...'; },
        },
    });

    $('#buscadorPreguntas').on('select2:select', function(e) {
        var pid = e.params.data.id;
        $('#cardDatosEstablecimiento').slideUp(150);
        setTimeout(function() {
            var el = document.getElementById('preg-' + pid);
            if (el) {
                // Compatible con Windows Chrome — fallback a scrollTop si smooth no funciona
                try {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } catch(err) {
                    el.scrollIntoView(true);
                }
                $(el).css('background', '#fef9c3');
                setTimeout(function() { $(el).css('background', ''); }, 1500);
            }
        }, 200);
    });

    $('#buscadorPreguntas').on('select2:clear', function() {
        $('#cardDatosEstablecimiento').slideDown(150);
        $(this).val(null).trigger('change');
    });
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function mostrarToast(msg, tipo) {
    var color = tipo === 'success' ? '#22c55e' : '#ef4444';
    var $t = $('<div style="position:fixed;bottom:24px;right:24px;background:' + color + ';color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">' + msg + '</div>');
    $('body').append($t);
    setTimeout(function() { $t.fadeOut(400, function() { $t.remove(); }); }, 3000);
}
</script>
@endsection