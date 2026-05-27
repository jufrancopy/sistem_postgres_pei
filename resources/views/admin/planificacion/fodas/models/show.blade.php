@extends('layouts.master')
@section('title', 'Categorías — ' . $category->name)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-sitemap mr-2"></i>{{ $category->name }}</h4>
        <p class="card-category">Categorías del modelo · Propietario: {{ $category->owner }}</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foda-models.index') }}">Modelos FODA</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Info del modelo ── --}}
        @if($category->description)
        <div class="alert alert-light border-left-info mb-4" style="border-left:4px solid #17a2b8">
            <small class="text-muted font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Descripción del modelo</small>
            <p class="mb-0 mt-1 small">{{ strip_tags($category->description) }}</p>
        </div>
        @endif

        {{-- ── Barra de acciones ── --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="badge badge-info mr-2"><i class="fa fa-cubes mr-1"></i>Modelo</span>
                <span class="text-muted small">
                    Las categorías agrupan los aspectos por ambiente (Interno / Externo).
                </span>
            </div>
            <button class="btn btn-success" id="btnNuevaCategoria">
                <i class="fa fa-plus mr-1"></i> Nueva Categoría
            </button>
        </div>

        {{-- ── Tabla de categorías ── --}}
        <div class="table-responsive">
            <table class="table table-hover" id="tablaCategoria">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th class="text-center">Ambiente</th>
                        <th>Descripción</th>
                        <th class="text-center" style="width:180px">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

{{-- ══ MODAL Crear / Editar Categoría ══ --}}
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header card-header-info mb-0">
                <h5 class="modal-title mb-0" id="modalCategoriaTitulo">Nueva Categoría</h5>
            </div>
            <div class="modal-body pt-3">
                <form id="formCategoria">
                    <input type="hidden" id="cat_model_id" name="model_id">
                    <input type="hidden" name="type" value="category">
                    <input type="hidden" name="parent_id" value="{{ $category->id }}">
                    <input type="hidden" name="owner" value="{{ $category->owner }}">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="font-weight-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="cat_name" class="form-control"
                                    placeholder="Ej: Capacidades Tecnológicas">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Ambiente <span class="text-danger">*</span></label>
                                <select name="environment" id="cat_environment" class="form-control">
                                    <option value="">Seleccionar...</option>
                                    <option value="Interno">Análisis Interno</option>
                                    <option value="Externo">Análisis Externo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Descripción</label>
                        <textarea name="description" id="cat_description" class="form-control" rows="3"
                            placeholder="Descripción de la categoría..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarCategoria">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL Ver Aspectos ══ --}}
<div class="modal fade" id="modalAspectos" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header card-header-success mb-0">
                <h5 class="modal-title mb-0" id="modalAspectosTitulo">Aspectos</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="tablaAspectos">
                        <thead class="thead-light">
                            <tr>
                                <th>Aspecto</th>
                                <th>Referencia / Descripción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="sinAspectos" class="alert alert-info d-none">
                    <i class="fa fa-info-circle mr-1"></i> Esta categoría aún no tiene aspectos definidos.
                    <a href="{{ route('foda-models-getAspects', '__ID__') }}" id="linkAgregarAspectos" class="alert-link">Agregar aspectos</a>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="btnIrAspectos" class="btn btn-success btn-sm">
                    <i class="fa fa-plus mr-1"></i> Gestionar Aspectos
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── CKEditor para descripción de categoría ────────────────────────────────
    var catDescEditor;
    ClassicEditor
        .create(document.querySelector('#cat_description'))
        .then(editor => { catDescEditor = editor; })
        .catch(err => { console.error(err); });

    // ── Select2 para ambiente ─────────────────────────────────────────────────
    $('#cat_environment').select2({
        dropdownParent: $('#modalCategoria'),
        placeholder: 'Seleccionar ambiente...',
        allowClear: false
    });

    // ── DataTable de categorías ───────────────────────────────────────────────
    var table = $('#tablaCategoria').DataTable({
        processing: true,
        serverSide: true,
        language: {
            emptyTable: 'Sin categorías — creá la primera',
            search: 'Buscar:', zeroRecords: 'Sin resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_',
            paginate: { next: 'Siguiente', previous: 'Anterior' }
        },
        ajax: '{{ route('foda-models.show', $category->id) }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, width: '40px' },
            { data: 'name' },
            {
                data: 'environment', className: 'text-center',
                render: function(d) {
                    var color = d === 'Interno' ? 'primary' : 'success';
                    return d ? '<span class="badge badge-' + color + '">' + d + '</span>' : '—';
                }
            },
            {
                data: 'description',
                render: function(d) {
                    if (!d) return '<span class="text-muted">—</span>';
                    var txt = $('<div>').html(d).text();
                    return txt.length > 80 ? txt.substring(0, 80) + '…' : txt;
                }
            },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    // ── Nueva categoría ───────────────────────────────────────────────────────
    $('#btnNuevaCategoria').on('click', function() {
        $('#modalCategoriaTitulo').text('Nueva Categoría');
        $('#formCategoria')[0].reset();
        $('#cat_model_id').val('');
        $('#cat_environment').val('').trigger('change');
        if (catDescEditor) catDescEditor.setData('');
        $('#modalCategoria').modal('show');
    });

    // ── Editar categoría ──────────────────────────────────────────────────────
    $(document).on('click', '.editCategory', function() {
        var id = $(this).data('id');
        $.get('{{ url('foda-models') }}/' + id + '/edit', function(data) {
            $('#modalCategoriaTitulo').text('Editar: ' + data.name);
            $('#cat_model_id').val(data.id);
            $('#cat_name').val(data.name);
            $('#cat_environment').val(data.environment).trigger('change');
            if (catDescEditor) catDescEditor.setData(data.description || '');
            $('#modalCategoria').modal('show');
        });
    });

    // ── Guardar categoría ─────────────────────────────────────────────────────
    $('#btnGuardarCategoria').on('click', function() {
        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        var formData = new FormData($('#formCategoria')[0]);
        if (catDescEditor) formData.set('description', catDescEditor.getData());

        $.ajax({
            url: '{{ route('foda-models.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#modalCategoria').modal('hide');
                toastr.success('Categoría guardada.');
                table.draw();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors || {};
                $.each(errors, function(k, v) { toastr.error(v); });
            },
            complete: function() {
                btn.html('<i class="fa fa-save mr-1"></i> Guardar').prop('disabled', false);
            }
        });
    });

    // ── Ver aspectos ──────────────────────────────────────────────────────────
    $(document).on('click', '.showAspects', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre') || 'Categoría';

        $('#modalAspectosTitulo').text('Aspectos — ' + nombre);
        $('#tablaAspectos tbody').empty();
        $('#sinAspectos').addClass('d-none');
        $('#btnIrAspectos').attr('href', '{{ url('foda-models') }}/' + id + '/getAspects');
        $('#modalAspectos').modal('show');

        $.get('{{ url('foda-models') }}/' + id + '/showAspects', function(data) {
            if (!data.aspects || data.aspects.length === 0) {
                $('#sinAspectos').removeClass('d-none')
                    .find('#linkAgregarAspectos')
                    .attr('href', '{{ url('foda-models') }}/' + id + '/getAspects');
                return;
            }
            $.each(data.aspects, function(i, a) {
                $('#tablaAspectos tbody').append(
                    '<tr><td>' + a.name + '</td><td class="text-muted small">' +
                    ($('<div>').html(a.description || '').text() || '—') + '</td></tr>'
                );
            });
        });
    });

    // ── Eliminar categoría ────────────────────────────────────────────────────
    $(document).on('click', '.deleteCategory', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar categoría?',
            text: 'Se eliminarán también todos sus aspectos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, eliminar'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.ajax({
                    url: '{{ url('foda-models') }}/' + id,
                    type: 'DELETE',
                    success: function() { toastr.success('Categoría eliminada.'); table.draw(); },
                    error: function() { toastr.error('Error al eliminar.'); }
                });
            }
        });
    });
});
</script>
@stop
