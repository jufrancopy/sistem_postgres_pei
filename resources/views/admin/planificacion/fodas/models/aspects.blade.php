@extends('layouts.master')
@section('title', 'Aspectos — ' . $category->name)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-tags mr-2"></i>{{ $category->name }}
        </h4>
        <p class="card-category">
            Aspectos de la categoría ·
            <span class="badge badge-{{ $category->environment === 'Interno' ? 'primary' : 'success' }} ml-1">
                {{ $category->environment }}
            </span>
        </p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foda-models.index') }}">Modelos FODA</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('foda-models.show', $category->parent_id) }}">Categorías</a>
            </li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Contexto de la categoría ── --}}
        <div class="row mb-4">
            <div class="col-md-8">
                @if($category->description)
                <div class="alert alert-light border-left-{{ $category->environment === 'Interno' ? 'primary' : 'success' }}"
                     style="border-left:4px solid {{ $category->environment === 'Interno' ? '#007bff' : '#28a745' }}">
                    <small class="text-muted font-weight-bold text-uppercase" style="font-size:.7rem;letter-spacing:.05em">
                        Descripción de la categoría
                    </small>
                    <p class="mb-0 mt-1 small">{{ strip_tags($category->description) }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card border shadow-sm">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Ambiente</small>
                            <span class="badge badge-{{ $category->environment === 'Interno' ? 'primary' : 'success' }}">
                                {{ $category->environment }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Propietario</small>
                            <small class="font-weight-bold">{{ $category->owner ?? '—' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Barra de acciones ── --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">
                <i class="fa fa-info-circle mr-1"></i>
                Los aspectos son los ítems concretos que se evalúan en el análisis FODA
                (Ej: "Infraestructura tecnológica", "Competencia del mercado").
            </span>
            <button class="btn btn-success" id="btnNuevoAspecto">
                <i class="fa fa-plus mr-1"></i> Nuevo Aspecto
            </button>
        </div>

        {{-- ── Tabla de aspectos ── --}}
        <div class="table-responsive">
            <table class="table table-hover" id="tablaAspectos">
                <thead class="thead-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Nombre del Aspecto</th>
                        <th>Descripción / Referencia</th>
                        <th class="text-center" width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

{{-- ══ MODAL Crear / Editar Aspecto ══ --}}
<div class="modal fade" id="modalAspecto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card-header card-header-info mb-0">
                <h5 class="modal-title mb-0" id="modalAspectoTitulo">Nuevo Aspecto</h5>
            </div>
            <div class="modal-body pt-3">
                <form id="formAspecto">
                    <input type="hidden" id="asp_model_id" name="model_id">
                    <input type="hidden" name="type"        value="aspect">
                    <input type="hidden" name="parent_id"   value="{{ $category->id }}">
                    <input type="hidden" name="owner"       value="{{ $category->owner }}">
                    <input type="hidden" name="environment" value="{{ $category->environment }}">

                    <div class="form-group">
                        <label class="font-weight-bold">
                            Nombre del Aspecto <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="asp_name" class="form-control"
                            placeholder="Ej: Infraestructura tecnológica actualizada">
                        <small class="text-muted">
                            Debe ser concreto y medible. Evitá términos vagos como "buena gestión".
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Descripción / Referencia</label>
                        <textarea name="description" id="asp_description" class="form-control" rows="3"
                            placeholder="Contexto, fuente de datos o referencia del aspecto..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarAspecto">
                    <i class="fa fa-save mr-1"></i> Guardar Aspecto
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

    // ── CKEditor para descripción del aspecto ─────────────────────────────────
    var aspDescEditor;
    ClassicEditor
        .create(document.querySelector('#asp_description'))
        .then(editor => { aspDescEditor = editor; })
        .catch(err => { console.error(err); });

    // ── DataTable de aspectos ─────────────────────────────────────────────────
    var table = $('#tablaAspectos').DataTable({
        processing: true,
        serverSide: true,
        language: {
            emptyTable: 'Sin aspectos — agregá el primero con el botón verde',
            search: 'Buscar:', zeroRecords: 'Sin resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_',
            paginate: { next: 'Siguiente', previous: 'Anterior' }
        },
        ajax: '{{ route('foda-models-getAspects', $category->id) }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            {
                data: 'description',
                render: function(d) {
                    if (!d) return '<span class="text-muted">—</span>';
                    var txt = $('<div>').html(d).text();
                    return '<span title="' + txt + '">' +
                        (txt.length > 100 ? txt.substring(0, 100) + '…' : txt) +
                        '</span>';
                }
            },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    // ── Nuevo aspecto ─────────────────────────────────────────────────────────
    $('#btnNuevoAspecto').on('click', function() {
        $('#modalAspectoTitulo').text('Nuevo Aspecto');
        $('#formAspecto')[0].reset();
        $('#asp_model_id').val('');
        if (aspDescEditor) aspDescEditor.setData('');
        $('#modalAspecto').modal('show');
        setTimeout(function() { $('#asp_name').focus(); }, 400);
    });

    // ── Editar aspecto ────────────────────────────────────────────────────────
    $(document).on('click', '.editAspect', function() {
        var id = $(this).data('id');
        $.get('{{ url('foda-models') }}/' + id + '/edit', function(data) {
            $('#modalAspectoTitulo').text('Editar: ' + data.name);
            $('#asp_model_id').val(data.id);
            $('#asp_name').val(data.name);
            if (aspDescEditor) aspDescEditor.setData(data.description || '');
            $('#modalAspecto').modal('show');
        });
    });

    // ── Guardar aspecto ───────────────────────────────────────────────────────
    $('#btnGuardarAspecto').on('click', function() {
        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin mr-1"></i> Guardando...').prop('disabled', true);

        var formData = new FormData($('#formAspecto')[0]);
        if (aspDescEditor) formData.set('description', aspDescEditor.getData());

        $.ajax({
            url: '{{ route('foda-models.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#modalAspecto').modal('hide');
                toastr.success('Aspecto guardado correctamente.');
                table.draw();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors || {};
                $.each(errors, function(k, v) { toastr.error(v); });
            },
            complete: function() {
                btn.html('<i class="fa fa-save mr-1"></i> Guardar Aspecto').prop('disabled', false);
            }
        });
    });

    // ── Eliminar aspecto ──────────────────────────────────────────────────────
    $(document).on('click', '.deleteAspect', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar aspecto?',
            text: 'Esta acción no se puede revertir.',
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
                    success: function() { toastr.success('Aspecto eliminado.'); table.draw(); },
                    error: function() { toastr.error('Error al eliminar.'); }
                });
            }
        });
    });
});
</script>
@stop
