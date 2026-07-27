@extends('layouts.master')
@section('title', 'Tipología: ' . $tipologia)

@push('styles')
<style>
.tabla-reglas th { font-size:.78rem; font-weight:600; white-space:nowrap; }
.tabla-reglas td { font-size:.82rem; vertical-align:middle; }
.tabla-reglas tr:hover td { background:#fafafa; }
.seccion-grupo { background:#f1f5f9; font-weight:700; font-size:.75rem; color:#475569; text-transform:uppercase; letter-spacing:.05em; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title"><i class="fa fa-sitemap mr-2"></i>Tipología: {{ $tipologia }}</h4>
        <p class="card-category">Configuración de secciones aplicables y requeridas</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.formularios.index') }}">Formularios</a></li>
            <li class="breadcrumb-item active">{{ $tipologia }}</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <small class="text-muted">
                    <i class="fa fa-info-circle mr-1"></i>
                    Marcá qué secciones <strong>aplican</strong> para esta tipología y cuáles son <strong>requeridas</strong>.
                    La condición limita la sección a casos específicos del establecimiento.
                </small>
            </div>
            <div style="gap:8px" class="d-flex">
                <button class="btn btn-outline-secondary btn-sm" onclick="abrirRenombrar()">
                    <i class="fa fa-edit mr-1"></i>Renombrar tipología
                </button>
                <button class="btn btn-danger btn-sm" onclick="guardarTodo()">
                    <i class="fa fa-save mr-1"></i>Guardar cambios
                </button>
            </div>
        </div>

        <div id="msgGuardar"></div>

        <div class="table-responsive">
            <table class="table table-sm tabla-reglas">
                <thead class="thead-light">
                    <tr>
                        <th style="width:45%">Sección</th>
                        <th class="text-center" style="width:10%">Aplica</th>
                        <th class="text-center" style="width:10%">Requerida</th>
                        <th>Condición <small class="text-muted font-weight-normal">(opcional)</small></th>
                    </tr>
                </thead>
                <tbody>
                    @php $seccionActual = ''; @endphp
                    @foreach($secciones as $s)
                        @if($s['seccion'] !== $seccionActual)
                            @php $seccionActual = $s['seccion']; @endphp
                            <tr>
                                <td colspan="4" class="seccion-grupo py-2 px-3">{{ $s['seccion'] }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="pl-4">
                                {{ $s['sub_seccion'] ?: $s['seccion'] }}
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="chk-aplica" data-id="{{ $s['id'] }}"
                                    {{ $s['regla'] && $s['regla']->aplica ? 'checked' : '' }}
                                    onchange="syncRequerida(this)">
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="chk-requerida" data-id="{{ $s['id'] }}"
                                    {{ $s['regla'] && $s['regla']->requerida ? 'checked' : '' }}
                                    {{ !($s['regla'] && $s['regla']->aplica) ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm inp-condicion" data-id="{{ $s['id'] }}"
                                    value="{{ $s['regla']?->condicion ?? '' }}"
                                    placeholder="Ej: Solo si tiene internacion"
                                    {{ !($s['regla'] && $s['regla']->aplica) ? 'disabled' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-right mt-3">
            <button class="btn btn-danger" onclick="guardarTodo()">
                <i class="fa fa-save mr-1"></i>Guardar cambios
            </button>
        </div>

    </div>
</div>

{{-- Modal renombrar --}}
<div class="modal fade" id="modalRenombrar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header card-header-danger" style="background:linear-gradient(135deg,#c62828,#e91e63)">
                <h5 class="modal-title text-white"><i class="fa fa-edit mr-2"></i>Renombrar tipología</h5>
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
                <button class="btn btn-danger" onclick="guardarRenombrar()"><i class="fa fa-save mr-1"></i>Renombrar</button>
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

function syncRequerida(chk) {
    var id = $(chk).data('id');
    var aplica = chk.checked;
    $('[data-id="' + id + '"].chk-requerida').prop('disabled', !aplica);
    $('[data-id="' + id + '"].inp-condicion').prop('disabled', !aplica);
    if (!aplica) {
        $('[data-id="' + id + '"].chk-requerida').prop('checked', false);
        $('[data-id="' + id + '"].inp-condicion').val('');
    }
}

function guardarTodo() {
    var reglas = {};
    $('.chk-aplica').each(function() {
        var id = $(this).data('id');
        reglas[id] = {
            aplica:    this.checked ? 1 : 0,
            requerida: $('[data-id="' + id + '"].chk-requerida').is(':checked') ? 1 : 0,
            condicion: $('[data-id="' + id + '"].inp-condicion').val() || null,
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
