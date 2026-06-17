@extends('layouts.master')
@section('title', 'Sección: ' . $seccion->getNombreCompletoAttribute())

@push('styles')
<style>
.pregunta-card { border:1px solid #e5e7eb; border-radius:8px; margin-bottom:8px; background:#fff; transition:box-shadow .15s; }
.pregunta-card:hover { box-shadow:0 2px 8px rgba(0,0,0,.08); }
.pregunta-card.inactiva { opacity:.5; background:#f8fafc; }
.pregunta-header { padding:12px 16px; display:flex; align-items:flex-start; gap:12px; }
.badge-tipo { background:#dbeafe; color:#1e40af; font-size:.68rem; padding:2px 7px; border-radius:8px; }
.badge-mapeada { background:#d1fae5; color:#065f46; font-size:.68rem; padding:2px 7px; border-radius:8px; }
.badge-sin-mapeo { background:#fef9c3; color:#854d0e; font-size:.68rem; padding:2px 7px; border-radius:8px; }
.badge-inactiva { background:#f1f5f9; color:#94a3b8; font-size:.68rem; padding:2px 7px; border-radius:8px; }
.btn-icon { width:30px; height:30px; border-radius:6px; border:1px solid #e5e7eb; background:#fff; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; font-size:.75rem; transition:all .15s; }
.btn-icon:hover { background:#f1f5f9; }
.btn-icon.danger:hover { background:#fee2e2; border-color:#fca5a5; color:#dc2626; }
.drag-handle { cursor:grab; color:#cbd5e1; padding:0 4px; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title"><i class="fa fa-wpforms mr-2"></i>{{ $seccion->getNombreCompletoAttribute() }}</h4>
        <p class="card-category">Gestión de preguntas de la sección</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">RIISS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.formularios.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active">{{ $seccion->seccion }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- Header de sección --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1 font-weight-bold">{{ $seccion->getNombreCompletoAttribute() }}</h5>
                <small class="text-muted">{{ $seccion->preguntas->count() }} preguntas · {{ $seccion->preguntas->where('activa', true)->count() }} activas</small>
            </div>
            <div style="gap:8px" class="d-flex">
                <button class="btn btn-outline-secondary btn-sm" onclick="abrirEditarSeccion()">
                    <i class="fa fa-edit mr-1"></i>Editar sección
                </button>
                <button class="btn btn-danger btn-sm" onclick="abrirNuevaPregunta()">
                    <i class="fa fa-plus mr-1"></i>Nueva pregunta
                </button>
            </div>
        </div>

        {{-- Toggle mostrar inactivas --}}
        <div class="mb-3">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="mostrarInactivas" onchange="toggleInactivas(this.checked)">
                <label class="custom-control-label small" for="mostrarInactivas">Mostrar preguntas inactivas</label>
            </div>
        </div>

        {{-- Lista de preguntas --}}
        <div id="listaPreguntas">
            @forelse($seccion->preguntas->sortBy('orden') as $p)
            <div class="pregunta-card {{ $p->activa ? '' : 'inactiva' }}" data-id="{{ $p->id }}" data-activa="{{ $p->activa ? '1' : '0' }}">
                <div class="pregunta-header">
                    <span class="drag-handle mt-1"><i class="fa fa-grip-vertical"></i></span>
                    <span class="text-muted small mt-1" style="min-width:24px">{{ $p->orden }}.</span>
                    <div style="flex:1">
                        <div class="mb-1">{{ $p->pregunta }}</div>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;align-items:center">
                            <span class="badge-tipo">{{ $p->tipo_respuesta }}</span>
                            @if($p->servicio_cartera_grupo)
                                <span class="badge-mapeada"><i class="fa fa-link mr-1"></i>{{ $p->servicio_cartera_grupo }}</span>
                            @else
                                <span class="badge-sin-mapeo"><i class="fa fa-unlink mr-1"></i>Sin mapeo</span>
                            @endif
                            @if($p->especialidad_relacionada)
                                <span class="badge-tipo">{{ $p->especialidad_relacionada }}</span>
                            @endif
                            @if(!$p->activa)
                                <span class="badge-inactiva">Inactiva</span>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;gap:4px;flex-shrink:0">
                        <button class="btn-icon" onclick="abrirMapeo({{ $p->id }}, '{{ addslashes($p->pregunta) }}', '{{ $p->servicio_cartera_grupo }}', '{{ $p->especialidad_relacionada }}')" title="Mapear a cartera">
                            <i class="fa fa-link text-primary"></i>
                        </button>
                        <button class="btn-icon" onclick="abrirEditar({{ $p->id }}, '{{ addslashes($p->pregunta) }}', '{{ $p->tipo_respuesta }}', {{ $p->orden }})" title="Editar">
                            <i class="fa fa-edit text-secondary"></i>
                        </button>
                        <button class="btn-icon" onclick="toggleActiva({{ $p->id }}, {{ $p->activa ? 1 : 0 }})" title="{{ $p->activa ? 'Desactivar' : 'Activar' }}">
                            <i class="fa fa-{{ $p->activa ? 'eye-slash' : 'eye' }} text-warning"></i>
                        </button>
                        <button class="btn-icon danger" onclick="eliminarPregunta({{ $p->id }})" title="Eliminar">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">Sin preguntas en esta sección.</div>
            @endforelse
        </div>

    </div>
</div>

{{-- Modal editar sección --}}
<div class="modal fade" id="modalSeccion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-header-danger" style="background:linear-gradient(135deg,#c62828,#e91e63)">
                <h5 class="modal-title text-white"><i class="fa fa-edit mr-2"></i>Editar sección</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="small font-weight-bold">Nombre de sección</label>
                    <input type="text" id="secNombre" class="form-control" value="{{ $seccion->seccion }}">
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold">Sub-sección</label>
                    <input type="text" id="secSubNombre" class="form-control" value="{{ $seccion->sub_seccion }}">
                </div>
                <div id="secMsg"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" onclick="guardarSeccion()"><i class="fa fa-save mr-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal nueva / editar pregunta --}}
<div class="modal fade" id="modalPregunta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-danger" style="background:linear-gradient(135deg,#c62828,#e91e63)">
                <h5 class="modal-title text-white" id="modalPreguntaTitulo"><i class="fa fa-plus mr-2"></i>Nueva pregunta</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="preguntaEditId">
                <div class="form-group">
                    <label class="small font-weight-bold">Texto de la pregunta <span class="text-danger">*</span></label>
                    <textarea id="preguntaTexto" class="form-control" rows="3" placeholder="Ej: ¿El establecimiento cuenta con vacunatorio?"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold">Tipo de respuesta <span class="text-danger">*</span></label>
                            <select id="preguntaTipo" class="form-control">
                                <option value="si_no">Sí / No</option>
                                <option value="si_no_na">Sí / No / No aplica</option>
                                <option value="texto">Texto libre</option>
                                <option value="numero">Número</option>
                                <option value="lista">Lista</option>
                                <option value="checklist">Checklist</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold">Orden</label>
                            <input type="number" id="preguntaOrden" class="form-control" min="1">
                        </div>
                    </div>
                </div>
                <div id="preguntaMsg"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" onclick="guardarPregunta()"><i class="fa fa-save mr-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal mapeo --}}
<div class="modal fade" id="modalMapeo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1e40af,#3b82f6)">
                <h5 class="modal-title text-white"><i class="fa fa-link mr-2"></i>Mapear a cartera de servicios</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small border-left pl-2 mb-3" id="mapeoTextoPregunta"></p>
                <div class="form-group">
                    <label class="small font-weight-bold">Grupo de servicio (cartera)</label>
                    <input type="text" id="mapeoGrupo" class="form-control" placeholder="Ej: Promoción y Prevención, Inmunizaciones...">
                    <small class="text-muted">Debe coincidir exactamente con el grupo en la cartera de servicios</small>
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold">Especialidad relacionada</label>
                    <input type="text" id="mapeoEspecialidad" class="form-control" placeholder="Ej: MEDICINA PREVENTIVA">
                </div>
                <div id="mapeoMsg"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarMapeo()"><i class="fa fa-save mr-1"></i>Guardar mapeo</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var SECCION_URL  = '/riiss/formularios/secciones/{{ $seccion->id }}';
var PREGUNTAS_URL= '/riiss/formularios/secciones/{{ $seccion->id }}/preguntas';
var PREGUNTA_URL = '/riiss/formularios/preguntas/';
var CSRF         = '{{ csrf_token() }}';
var preguntaMapeoId = null;
var preguntaEditId  = null;

// Toggle inactivas
function toggleInactivas(mostrar) {
    $('.pregunta-card[data-activa="0"]').toggle(mostrar);
}

// ── Sección ──────────────────────────────────────────
function abrirEditarSeccion() { $('#modalSeccion').modal('show'); }

function guardarSeccion() {
    $.ajax({
        url: SECCION_URL, method: 'POST', contentType: 'application/json',
        data: JSON.stringify({ _token: CSRF, _method: 'PATCH', seccion: $('#secNombre').val(), sub_seccion: $('#secSubNombre').val() }),
        success: function(r) {
            if (r.ok) { $('#modalSeccion').modal('hide'); location.reload(); }
            else $('#secMsg').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
        }
    });
}

// ── Preguntas ─────────────────────────────────────────
function abrirNuevaPregunta() {
    preguntaEditId = null;
    $('#modalPreguntaTitulo').html('<i class="fa fa-plus mr-2"></i>Nueva pregunta');
    $('#preguntaTexto').val('');
    $('#preguntaTipo').val('si_no');
    $('#preguntaOrden').val('');
    $('#preguntaMsg').html('');
    $('#modalPregunta').modal('show');
}

function abrirEditar(id, texto, tipo, orden) {
    preguntaEditId = id;
    $('#modalPreguntaTitulo').html('<i class="fa fa-edit mr-2"></i>Editar pregunta');
    $('#preguntaTexto').val(texto);
    $('#preguntaTipo').val(tipo);
    $('#preguntaOrden').val(orden);
    $('#preguntaMsg').html('');
    $('#modalPregunta').modal('show');
}

function guardarPregunta() {
    var texto = $('#preguntaTexto').val().trim();
    if (!texto) { $('#preguntaMsg').html('<div class="alert alert-warning py-2">El texto es requerido.</div>'); return; }

    var url    = preguntaEditId ? PREGUNTA_URL + preguntaEditId : PREGUNTAS_URL;
    var method = preguntaEditId ? 'PATCH' : 'POST';

    $.ajax({
        url: url, method: 'POST', contentType: 'application/json',
        data: JSON.stringify({
            _token: CSRF, _method: method,
            pregunta:       texto,
            tipo_respuesta: $('#preguntaTipo').val(),
            orden:          $('#preguntaOrden').val() || null,
        }),
        success: function(r) {
            if (r.ok) { $('#modalPregunta').modal('hide'); location.reload(); }
            else $('#preguntaMsg').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
        },
        error: function(xhr) {
            var msg = xhr.responseJSON?.message || 'Error al guardar.';
            $('#preguntaMsg').html('<div class="alert alert-danger py-2">' + msg + '</div>');
        }
    });
}

function toggleActiva(id, activa) {
    $.ajax({
        url: PREGUNTA_URL + id, method: 'POST', contentType: 'application/json',
        data: JSON.stringify({ _token: CSRF, _method: 'PATCH', activa: activa ? 0 : 1 }),
        success: function(r) { if (r.ok) location.reload(); }
    });
}

function eliminarPregunta(id) {
    if (!confirm('¿Eliminar permanentemente esta pregunta? Esta acción no se puede deshacer.')) return;
    $.ajax({
        url: PREGUNTA_URL + id, method: 'POST',
        data: { _token: CSRF, _method: 'DELETE', force: 1 },
        success: function(r) { if (r.ok) location.reload(); }
    });
}

// ── Mapeo ─────────────────────────────────────────────
function abrirMapeo(id, texto, grupo, esp) {
    preguntaMapeoId = id;
    $('#mapeoTextoPregunta').text(texto);
    $('#mapeoGrupo').val(grupo || '');
    $('#mapeoEspecialidad').val(esp || '');
    $('#mapeoMsg').html('');
    $('#modalMapeo').modal('show');
}

function guardarMapeo() {
    $.ajax({
        url: PREGUNTA_URL + preguntaMapeoId + '/mapeo', method: 'POST', contentType: 'application/json',
        data: JSON.stringify({
            _token: CSRF, _method: 'PATCH',
            servicio_cartera_grupo:   $('#mapeoGrupo').val() || null,
            especialidad_relacionada: $('#mapeoEspecialidad').val() || null,
        }),
        success: function(r) {
            if (r.ok) { $('#modalMapeo').modal('hide'); location.reload(); }
            else $('#mapeoMsg').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
        }
    });
}
</script>
@endsection
