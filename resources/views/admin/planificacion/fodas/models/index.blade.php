@extends('layouts.master')
@section('title', 'Modelos FODA')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-cubes mr-2"></i>Modelos FODA</h4>
        <p class="card-category">Plantillas de análisis reutilizables — Categorías y Aspectos por modelo</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item active">Modelos FODA</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Barra de acciones ── --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-muted small">
                    <i class="fa fa-info-circle mr-1"></i>
                    Cada modelo define las <strong>categorías</strong> y <strong>aspectos</strong> que se analizan en el FODA.
                </span>
            </div>
            <button class="btn btn-success" id="btnNuevoModelo">
                <i class="fa fa-plus mr-1"></i> Nuevo Modelo
            </button>
        </div>

        {{-- ── Grid de modelos (cargado vía AJAX) ── --}}
        <div id="modelosGrid" class="row">
            <div class="col-12 text-center py-5 text-muted" id="loadingModelos">
                <i class="fa fa-spinner fa-spin fa-2x mb-2"></i><br>
                Cargando modelos...
            </div>
        </div>

    </div>
</div>

{{-- ══ MODAL Crear / Editar Modelo ══ --}}
<div class="modal fade" id="modalModelo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header card-header-info mb-0">
                <h5 class="modal-title mb-0" id="modalModeloTitulo">Nuevo Modelo FODA</h5>
            </div>
            <div class="modal-body pt-3">
                <form id="formModelo">
                    <input type="hidden" id="modelo_id" name="model_id">
                    <input type="hidden" name="type" value="root">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="font-weight-bold">Nombre del Modelo <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="modelo_name" class="form-control"
                                    placeholder="Ej: Modelo Estratégico IPS 2024">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Propietario <span class="text-danger">*</span></label>
                                <input type="text" name="owner" id="modelo_owner" class="form-control"
                                    placeholder="Ej: Dirección de Planificación">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Descripción técnica</label>
                        <textarea name="description" id="modelo_description" class="form-control" rows="3"
                            placeholder="Contexto y propósito del modelo..."></textarea>
                        <small class="text-muted">Describe el alcance y uso previsto de este modelo.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarModelo">
                    <i class="fa fa-save mr-1"></i> Guardar Modelo
                </button>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── CKEditor para descripción del modelo ──────────────────────────────────
    var modeloDescEditor;
    ClassicEditor
        .create(document.querySelector('#modelo_description'))
        .then(editor => { modeloDescEditor = editor; })
        .catch(err => { console.error(err); });

    // ── Cargar grid de modelos ────────────────────────────────────────────────
    function cargarModelos() {
        $.get('{{ route('foda-models.index') }}', function(resp) {
            var modelos = resp.data || [];
            var grid = $('#modelosGrid');
            grid.empty();

            if (modelos.length === 0) {
                grid.html(`
                    <div class="col-12">
                        <div class="alert alert-info text-center py-4">
                            <i class="fa fa-cubes fa-2x mb-2 d-block"></i>
                            <strong>Sin modelos creados</strong><br>
                            <small>Creá el primer modelo FODA para comenzar el análisis.</small>
                        </div>
                    </div>
                `);
                return;
            }

            modelos.forEach(function(m) {
                var desc = m.description
                    ? '<p class="text-muted small mb-0" style="line-height:1.4">' + $('<div>').text(m.description).html().substring(0, 120) + (m.description.length > 120 ? '…' : '') + '</p>'
                    : '<p class="text-muted small mb-0 font-italic">Sin descripción</p>';

                grid.append(`
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow h-100 border-left-info" style="border-left:4px solid #17a2b8!important">
                            <div class="card-body pb-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="font-weight-bold mb-1" style="font-size:.95rem">${m.name}</h6>
                                        <small class="text-muted"><i class="fa fa-user mr-1"></i>${m.owner || '—'}</small>
                                    </div>
                                    <span class="badge badge-info" style="font-size:.7rem">Modelo</span>
                                </div>
                                ${desc}
                            </div>
                            <div class="card-footer bg-transparent pt-2 pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ url('foda-models') }}/${m.id}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-sitemap mr-1"></i> Ver Categorías
                                    </a>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary btnEditarModelo mr-1"
                                                data-id="${m.id}" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btnEliminarModelo"
                                                data-id="${m.id}" data-nombre="${m.name}" title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        }).fail(function() {
            $('#modelosGrid').html('<div class="col-12"><div class="alert alert-danger">Error al cargar los modelos.</div></div>');
        }).always(function() {
            $('#loadingModelos').remove();
        });
    }

    cargarModelos();

    // ── Nuevo modelo ─────────────────────────────────────────────────────────
    $('#btnNuevoModelo').on('click', function() {
        $('#modalModeloTitulo').text('Nuevo Modelo FODA');
        $('#formModelo')[0].reset();
        $('#modelo_id').val('');
        if (modeloDescEditor) modeloDescEditor.setData('');
        $('#modalModelo').modal('show');
    });

    // ── Editar modelo ─────────────────────────────────────────────────────────
    $(document).on('click', '.btnEditarModelo', function() {
        var id = $(this).data('id');
        $.get('{{ url('foda-models') }}/' + id + '/edit', function(data) {
            $('#modalModeloTitulo').text('Editar Modelo FODA');
            $('#modelo_id').val(data.id);
            $('#modelo_name').val(data.name);
            $('#modelo_owner').val(data.owner);
            if (modeloDescEditor) modeloDescEditor.setData(data.description || '');
            $('#modalModelo').modal('show');
        });
    });

    // ── Guardar (crear o editar) ──────────────────────────────────────────────
    $('#btnGuardarModelo').on('click', function() {
        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        var data = new FormData($('#formModelo')[0]);
        // Inyectar contenido del editor rico
        if (modeloDescEditor) data.set('description', modeloDescEditor.getData());

        $.ajax({
            url: '{{ route('foda-models.store') }}',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function() {
                $('#modalModelo').modal('hide');
                toastr.success('Modelo guardado correctamente.');
                cargarModelos();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors || {};
                $.each(errors, function(k, v) { toastr.error(v); });
            },
            complete: function() {
                btn.html('<i class="fa fa-save mr-1"></i> Guardar Modelo').prop('disabled', false);
            }
        });
    });

    // ── Eliminar modelo ───────────────────────────────────────────────────────
    $(document).on('click', '.btnEliminarModelo', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');

        Swal.fire({
            title: '¿Eliminar "' + nombre + '"?',
            text: 'Se eliminarán también todas sus categorías y aspectos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('foda-models') }}/' + id,
                    type: 'DELETE',
                    success: function() {
                        toastr.success('Modelo eliminado.');
                        cargarModelos();
                    },
                    error: function() { toastr.error('Error al eliminar.'); }
                });
            }
        });
    });
});
</script>
@stop
