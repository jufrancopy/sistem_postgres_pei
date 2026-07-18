@extends('layouts.master')
@section('title', 'Actividades')

@push('styles')
<style>
.tipo-badge { font-size:.7rem; padding:3px 8px; border-radius:20px; font-weight:600; }
.tipo-scrum  { background:#dbeafe; color:#1e40af; }
.tipo-kanba  { background:#fce7f3; color:#9d174d; }
.resp-badge  { font-size:.68rem; padding:2px 7px; border-radius:20px; background:#f1f5f9; color:#475569; margin:1px; display:inline-block; }
.dataTables_wrapper .dt-buttons { margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="material-icons mr-2" style="vertical-align:middle">rocket_launch</i>Actividades
        </h4>
        <p class="card-category">Gestión de actividades y tableros de tareas</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Actividades</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 font-weight-bold">
                <i class="fa fa-list mr-2 text-info"></i>Lista de actividades
            </h5>
            <button class="btn btn-info" id="createNewActivity">
                <i class="fa fa-plus mr-1"></i>Nueva Actividad
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm data-table" id="data-table">
                <thead class="thead-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fechas</th>
                        <th>Responsables</th>
                        <th class="text-center" width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

{{-- Modal crear/editar actividad --}}
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info" style="background:linear-gradient(135deg,#0288d1,#03a9f4)">
                <h5 class="modal-title text-white" id="activityHeading">Nueva Actividad</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="activityForm">
                    {{ csrf_field() }}
                    <input type="hidden" id="activity_id" name="activity_id">

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="small font-weight-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de la actividad">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold">Tipo</label>
                            <select name="type" id="type" class="form-control" style="width:100%">
                                <option value="">Seleccioná el tipo</option>
                                <option value="scrum">Scrum</option>
                                <option value="kanba">Kanban</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold">Descripción</label>
                            <textarea name="description" id="description" class="form-control" rows="2" placeholder="Descripción de la actividad..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold">Fecha inicio</label>
                            <input type="date" name="date_start" id="date_start" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small font-weight-bold">Fecha fin</label>
                            <input type="date" name="date_end" id="date_end" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="small font-weight-bold">Responsables</label>
                            <select name="responsible_id[]" id="responsibles" class="form-control" multiple style="width:100%"></select>
                        </div>
                    </div>

                    <div class="alert alert-danger errors d-none"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" id="saveBtn">
                    <i class="fa fa-save mr-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── DataTable ─────────────────────────────────────────────────────────────
    var table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', text: '<i class="fa fa-file-excel"></i>', titleAttr: 'Excel' },
            { extend: 'pdf',   text: '<i class="fa fa-file-pdf"></i>',   titleAttr: 'PDF' },
            { extend: 'print', text: '<i class="fa fa-print"></i>',      titleAttr: 'Imprimir' },
        ],
        language: {
            emptyTable:     'Sin actividades registradas',
            info:           'Mostrando _START_ a _END_ de _TOTAL_',
            infoEmpty:      '0 actividades',
            search:         'Buscar:',
            zeroRecords:    'Sin resultados',
            paginate: { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
        },
        ajax: '{{ route("globales.activities.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            {
                data: 'type', name: 'type',
                render: function(data) {
                    if (!data) return '<span class="text-muted small">—</span>';
                    var cls = data === 'scrum' ? 'tipo-scrum' : 'tipo-kanba';
                    var lbl = data === 'scrum' ? 'Scrum' : 'Kanban';
                    return '<span class="tipo-badge ' + cls + '">' + lbl + '</span>';
                }
            },
            {
                data: 'date_start', name: 'date_start', orderable: false, searchable: false,
                render: function(data, type, row) {
                    if (!row.date_start) return '<small class="text-muted">—</small>';
                    return '<small>' + (row.date_start || '') + ' → ' + (row.date_end || '') + '</small>';
                }
            },
            {
                data: 'responsibles', name: 'responsibles', orderable: false,
                render: function(data) {
                    if (!data) return '<small class="text-muted">—</small>';
                    return data.split(', ').map(function(r) {
                        return '<span class="resp-badge">' + r + '</span>';
                    }).join(' ');
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    // ── Select2 helpers ───────────────────────────────────────────────────────
    var getUsersUrl = '{{ route("globales.get-users") }}';
    function initTipoSelect() {
        $('#type').select2({ placeholder: 'Seleccioná el tipo', dropdownParent: $('#activityModal') });
    }

    function initResponsablesSelect(selected) {
        $('#responsibles').empty().select2({
            allowClear: true,
            placeholder: 'Buscar responsable...',
            dropdownParent: $('#activityModal'),
            ajax: {
                url: getUsersUrl, dataType: 'json', delay: 250,
                processResults: function(data) {
                    return { results: $.map(data, function(u) { return { id: u.id, text: u.name }; }) };
                }
            }
        });
        if (selected) {
            selected.forEach(function(r) {
                $('#responsibles').append(new Option(r.text, r.id, true, true)).trigger('change');
            });
        }
    }

    // ── Nueva actividad ───────────────────────────────────────────────────────
    $('#createNewActivity').click(function() {
        $('#activityHeading').text('Nueva Actividad');
        $('#activityForm')[0].reset();
        $('#activity_id').val('');
        $('.errors').addClass('d-none').text('');
        initTipoSelect();
        initResponsablesSelect(null);
        $('#activityModal').modal('show');
    });

    // ── Editar ────────────────────────────────────────────────────────────────
    $('body').on('click', '.editActivity', function() {
        var id = $(this).data('id');
        $.get('{{ route("globales.activities.index") }}/' + id + '/edit', function(data) {
            $('#activityHeading').text('Editar: ' + data.activity.name);
            $('#activity_id').val(data.activity.id);
            $('#name').val(data.activity.name);
            $('#description').val(data.activity.description);
            $('#date_start').val(data.activity.date_start);
            $('#date_end').val(data.activity.date_end);
            $('.errors').addClass('d-none').text('');
            initTipoSelect();
            $('#type').val(data.activity.type).trigger('change');
            initResponsablesSelect(data.responsiblesChecked);
            $('#activityModal').modal('show');
        });
    });

    // ── Guardar ───────────────────────────────────────────────────────────────
    $('#saveBtn').click(function() {
        var $btn = $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...');
        $.ajax({
            url:      '{{ route("globales.activities.store") }}',
            type:     'POST',
            data:     $('#activityForm').serialize(),
            dataType: 'json',
            success: function(data) {
                $('#activityModal').modal('hide');
                toastr.success(data.success);
                table.draw();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var msgs = Object.values(errors).flat().join('<br>');
                    $('.errors').removeClass('d-none').html(msgs);
                } else {
                    toastr.error('Error al guardar');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar');
            }
        });
    });

    // ── Eliminar ──────────────────────────────────────────────────────────────
    $('body').on('click', '.deleteActivity', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Eliminar actividad?',
            text: 'Se eliminarán también todas sus tareas.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url:  '{{ route("globales.activities.store") }}/' + id,
                    type: 'DELETE',
                    success: function() { toastr.success('Eliminado'); table.draw(); },
                    error:   function() { toastr.error('Error al eliminar'); }
                });
            }
        });
    });
});
</script>
@endsection
