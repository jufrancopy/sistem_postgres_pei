@extends('layouts.master')
@section('title', 'Tipología: ' . $tipologia)

@push('styles')
<style>
.tabla-reglas th { font-size:.78rem; font-weight:600; white-space:nowrap; }
.tabla-reglas td { font-size:.82rem; vertical-align:middle; }
.tabla-reglas tr:hover td { background:#fafafa; }
.hover-bg-light:hover { background-color: #f8f9fa !important; }
.btn-icon:hover { background:#f1f5f9; }
.seccion-grupo { background:#f1f5f9; font-weight:700; font-size:.75rem; color:#475569; text-transform:uppercase; letter-spacing:.05em; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info" style="background: linear-gradient(135deg, #00acc1, #26c6da); border-radius: 12px; box-shadow: 0 12px 26px rgba(0, 172, 193, 0.16);">
        <h4 class="card-title text-white"><i class="fa fa-sitemap mr-2"></i>Tipología: {{ $tipologia }}</h4>
        <p class="card-category text-white-75">Configuración de secciones aplicables y requeridas</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.formularios.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active">{{ $tipologia }}</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <div class="mb-3 mb-md-0">
                <small class="text-muted">
                    <i class="fa fa-info-circle mr-1"></i>
                    Marcá qué secciones <strong>aplican</strong> para esta tipología y cuáles son <strong>requeridas</strong>.
                    La condición limita la sección a casos específicos del establecimiento.
                </small>
            </div>
            <div style="gap:8px" class="d-flex flex-wrap">
                <button class="btn btn-outline-secondary btn-sm m-0" onclick="abrirRenombrar()">
                    <i class="fa fa-edit mr-1"></i>Renombrar tipología
                </button>
                <button class="btn btn-info btn-sm m-0" onclick="guardarTodo()">
                    <i class="fa fa-save mr-1"></i>Guardar cambios
                </button>
            </div>
        </div>

        <div id="msgGuardar"></div>

        <div class="table-responsive">
            <table class="table table-sm table-hover tabla-reglas w-100" id="tablaSecciones">
                <thead class="thead-light">
                    <tr>
                        <th style="width:20%">Sección Principal</th>
                        <th style="width:25%">Sub Módulo</th>
                        <th class="text-center" style="width:12%">¿Activo?</th>
                        <th class="text-center" style="width:12%">¿Obligatorio?</th>
                        <th style="width:20%">Condición Específica</th>
                        <th class="text-center" style="width:11%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($secciones as $s)
                        <tr>
                            <td class="align-middle text-muted">{{ $s['seccion'] }}</td>
                            <td class="align-middle text-dark font-weight-bold">{{ $s['sub_seccion'] ?: $s['seccion'] }}</td>
                            <td class="text-center align-middle">
                                <div class="custom-control custom-switch d-inline-block">
                                    <input type="checkbox" class="custom-control-input chk-aplica" id="aplica-{{ $s['id'] }}" data-id="{{ $s['id'] }}"
                                        {{ $s['regla'] && $s['regla']->aplica ? 'checked' : '' }}
                                        onchange="syncRequerida(this)">
                                    <label class="custom-control-label" for="aplica-{{ $s['id'] }}"></label>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="custom-control custom-switch d-inline-block">
                                    <input type="checkbox" class="custom-control-input chk-requerida" id="req-{{ $s['id'] }}" data-id="{{ $s['id'] }}"
                                        {{ $s['regla'] && $s['regla']->requerida ? 'checked' : '' }}
                                        {{ !($s['regla'] && $s['regla']->aplica) ? 'disabled' : '' }}>
                                    <label class="custom-control-label" for="req-{{ $s['id'] }}"></label>
                                </div>
                            </td>
                            <td class="align-middle">
                                <input type="text" class="form-control form-control-sm inp-condicion" data-id="{{ $s['id'] }}"
                                    value="{{ $s['regla']?->condicion ?? '' }}"
                                    placeholder="Ej: Solo si tiene internacion"
                                    {{ !($s['regla'] && $s['regla']->aplica) ? 'disabled' : '' }}>
                            </td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-sm btn-outline-info p-1 m-0" title="Editar preguntas de esta sección" onclick="abrirCamposModal('{{ route('riiss.formularios.secciones.show', $s['id']) }}?iframe=1', '{{ addslashes($s['sub_seccion'] ?: $s['seccion']) }}')">
                                    <i class="fa fa-list"></i> Campos
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right mt-3">
            <button class="btn btn-info" onclick="guardarTodo()">
                <i class="fa fa-save mr-1"></i>Guardar cambios
            </button>
        </div>

    </div>
</div>

{{-- Modal para iframe de Campos --}}
<div class="modal fade" id="modalCampos" tabindex="-1">
    <div class="modal-dialog modal-xl" style="height: 90vh;">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header card-header-info" style="background: linear-gradient(135deg, #00acc1, #26c6da);">
                <h5 class="modal-title text-white"><i class="fa fa-list mr-2"></i>Campos de la Sección</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="iframeCampos" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

{{-- Modal Renombrar Tipología --}}
<div class="modal fade" id="modalRenombrar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background: linear-gradient(135deg, #00acc1, #26c6da);">
                <h5 class="modal-title text-white"><i class="fa fa-edit mr-2"></i>Renombrar Tipología</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning py-2 small">
                    <i class="fa fa-exclamation-triangle mr-1"></i>
                    Esto actualizará el nombre en <strong>establecimientos</strong>, <strong>cartera de servicios</strong> y <strong>reglas del formulario</strong>.
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold">Nuevo nombre</label>
                    <input type="text" id="nuevoNombre" class="form-control">
                </div>
                <div id="renombrarMsg"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-info" onclick="guardarRenombrar()"><i class="fa fa-save mr-1"></i>Renombrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var UPDATE_URL   = '/riiss/formularios/tipologias/{{ urlencode($tipologia) }}/reglas';
var RENAME_URL   = '/riiss/formularios/tipologias/{{ urlencode($tipologia) }}/renombrar';
var CSRF = '{{ csrf_token() }}';

function syncRequerida(chkAplica) {
    var id = chkAplica.dataset.id;
    var chkReq = document.getElementById('req-' + id);
    var inpCond = document.querySelector('.inp-condicion[data-id="'+id+'"]');
    
    if (chkAplica.checked) {
        chkReq.disabled = false;
        inpCond.disabled = false;
        chkAplica.closest('tr').style.background = '#f0fbff';
    } else {
        chkReq.checked = false;
        chkReq.disabled = true;
        inpCond.value = '';
        inpCond.disabled = true;
        chkAplica.closest('tr').style.background = '';
    }
}

function abrirCamposModal(url, titulo) {
    $('#modalCampos .modal-title').html('<i class="fa fa-list mr-2"></i>' + titulo);
    $('#iframeCampos').attr('src', url);
    $('#modalCampos').modal('show');
}

// Limpiar iframe al cerrar modal
$('#modalCampos').on('hidden.bs.modal', function () {
    $('#iframeCampos').attr('src', '');
});

// Inicializar DataTables y pintar filas
document.addEventListener("DOMContentLoaded", function() {
    $('#tablaSecciones').DataTable({
        pageLength: 50,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        columnDefs: [
            { orderable: false, targets: [2, 3, 4, 5] } // Desactivar orden en toggles y botones
        ]
    });

    document.querySelectorAll('.chk-aplica:checked').forEach(chk => {
        chk.closest('tr').style.background = '#f0fbff';
    });
});

function guardarTodo() {
    var reglas = {};
    // Cuando se usa DataTables, hay que iterar sobre los nodos internos si hay paginación
    var table = $('#tablaSecciones').DataTable();
    table.$('.chk-aplica').each(function() {
        var id = $(this).data('id');
        reglas[id] = {
            aplica:    this.checked ? 1 : 0,
            requerida: table.$('#req-' + id).is(':checked') ? 1 : 0,
            condicion: table.$('.inp-condicion[data-id="'+id+'"]').val() || null,
        };
    });

    $.ajax({
        url: UPDATE_URL, method: 'POST', contentType: 'application/json',
        data: JSON.stringify({ _token: CSRF, reglas: reglas }),
        success: function(r) {
            if (r.ok) {
                $('#msgGuardar').html('<div class="alert alert-success py-2"><i class="fa fa-check mr-1"></i>' + r.message + '</div>');
                setTimeout(function() { $('#msgGuardar').html(''); }, 3000);
            }
        },
        error: function() { $('#msgGuardar').html('<div class="alert alert-danger py-2">Error al guardar.</div>'); }
    });
}

function abrirRenombrar() {
    $('#nuevoNombre').val('{{ $tipologia }}');
    $('#renombrarMsg').html('');
    $('#modalRenombrar').modal('show');
}

function guardarRenombrar() {
    var nuevo = $('#nuevoNombre').val().trim();
    if (!nuevo) return;
    $.ajax({
        url: RENAME_URL, method: 'POST', contentType: 'application/json',
        data: JSON.stringify({ _token: CSRF, _method: 'PATCH', nuevo_nombre: nuevo }),
        success: function(r) {
            if (r.ok) {
                $('#modalRenombrar').modal('hide');
                // Redirigir a la nueva URL
                window.location.href = '/riiss/formularios/tipologias/' + encodeURIComponent(nuevo);
            } else {
                $('#renombrarMsg').html('<div class="alert alert-danger py-2">' + r.message + '</div>');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON?.message || 'Error al renombrar.';
            $('#renombrarMsg').html('<div class="alert alert-danger py-2">' + msg + '</div>');
        }
    });
}
</script>
@endsection
