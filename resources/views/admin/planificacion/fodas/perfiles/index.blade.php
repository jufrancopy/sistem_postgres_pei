@extends('layouts.master')
@section('title', 'Perfiles FODA')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Perfiles de Análisis FODA</h4>
        <p class="card-category">Gestión de perfiles grupales e individuales</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item active">Perfiles FODA</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="mb-3">
            <div class="success"></div>
            <a class="btn btn-success" href="javascript:void(0)" id="createNewProfile">
                <i class="material-icons">add_box</i> Nuevo Perfil
            </a>
        </div>

        {{-- ── Tabs ── --}}
        <ul class="nav nav-pills nav-pills-info" id="fodaTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-grupal" data-toggle="tab" href="#panel-grupal" role="tab">
                    <i class="fa fa-users mr-1"></i> Grupales
                    <span class="badge badge-light ml-1" id="count-grupal">...</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-individual" data-toggle="tab" href="#panel-individual" role="tab">
                    <i class="fa fa-user mr-1"></i> Individuales
                    <span class="badge badge-light ml-1" id="count-individual">...</span>
                </a>
            </li>
        </ul>

        <div class="tab-content mt-3">

            {{-- ── Tab Grupal ── --}}
            <div class="tab-pane fade show active" id="panel-grupal" role="tabpanel">
                <div class="table-responsive mt-2">
                    <table class="table table-hover" id="table-grupal">
                        <thead class="text-info">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Contexto</th>
                                <th>Grupo</th>
                                <th>Modelo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            {{-- ── Tab Individual ── --}}
            <div class="tab-pane fade" id="panel-individual" role="tabpanel">
                <div class="table-responsive mt-2">
                    <table class="table table-hover" id="table-individual">
                        <thead class="text-info">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Contexto</th>
                                <th>Dependencia</th>
                                <th>Modelo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>{{-- tab-content --}}
    </div>{{-- card-body --}}
</div>{{-- card --}}

{{-- ── Modal ── --}}
<div class="modal fade" id="modalProfile" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalProfilelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="profileForm" name="profileForm" class="form-horizontal">
                    {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}

                    <div class="form-group">
                        {{ Form::label('name', 'Nombre:') }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('context', 'Contexto:') }}
                        {{ Form::text('context', null, ['class' => 'form-control', 'id' => 'context']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('type', 'Tipo:') }}
                        {!! Form::select('type', ['individual' => 'Individual', 'grupal' => 'Grupal'], null, [
                            'placeholder' => '', 'id' => 'type', 'style' => 'width:100%',
                        ]) !!}
                    </div>

                    <div class="form-group group_roots" style="display:none;">
                        {{ Form::label('group_root_id', 'Evento / Grupo Raíz:') }}
                        {!! Form::select('group_root_id', [], null, ['placeholder' => '', 'id' => 'group_roots', 'style' => 'width:100%']) !!}
                    </div>

                    <div class="form-group dependencies_roots" style="display:none;">
                        {{ Form::label('dependencies_roots', 'Dependencia Raíz:') }}
                        {!! Form::select('dependencies_roots', [], null, ['placeholder' => '', 'id' => 'dependencies_roots', 'style' => 'width:100%']) !!}
                    </div>

                    <div class="form-group groups" style="display:none;">
                        {{ Form::label('groups', 'Grupo de Análisis:') }}
                        {!! Form::select('group_id', [], null, ['placeholder' => '', 'id' => 'groups', 'style' => 'width:100%']) !!}
                    </div>

                    <div class="form-group dependencies">
                        {{ Form::label('dependency_id', 'Dependencia Responsable:') }}
                        {!! Form::select('dependency_id', [], null, ['id' => 'dependency', 'style' => 'width:100%']) !!}
                    </div>

                    <div class="form-group">
                        {{ Form::label('model_id', 'Modelo de Análisis:') }}
                        {!! Form::select('model_id', [], null, ['placeholder' => 'Seleccione el Modelo', 'id' => 'models', 'style' => 'width:100%']) !!}
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<style>
    .table th { font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; }
    .table td { vertical-align: middle; font-size: .85rem; }
</style>

<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var dtLang = {
        emptyTable: "No hay información", info: "Mostrando _START_ a _END_ de _TOTAL_",
        infoEmpty: "0 registros", search: "Buscar:", zeroRecords: "Sin resultados",
        paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
    };

    var dtButtons = [
        { extend: 'excel', text: '<i class="fa fa-file-excel"></i>', titleAttr: 'Excel' },
        { extend: 'pdf',   text: '<i class="fa fa-file-pdf"></i>',   titleAttr: 'PDF'   },
        { extend: 'print', text: '<i class="fa fa-print"></i>',      titleAttr: 'Imprimir' }
    ];

    // ── DataTable Grupal ─────────────────────────────────────
    var tableGrupal = $('#table-grupal').DataTable({
        processing: true, serverSide: true,
        dom: 'Bfrtip', buttons: dtButtons, language: dtLang,
        autoWidth: false,
        ajax: {
            url: "{{ route('foda-perfiles.index') }}",
            data: function(d) { d.type = 'grupal'; }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'context', name: 'context' },
            { data: 'dependency', name: 'dependency' },
            { data: 'model', name: 'model' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        drawCallback: function(s) {
            $('#count-grupal').text(s.fnRecordsTotal());
        }
    });

    // ── DataTable Individual ──────────────────────────────────
    var tableIndividual = $('#table-individual').DataTable({
        processing: true, serverSide: true,
        dom: 'Bfrtip', buttons: dtButtons, language: dtLang,
        autoWidth: false,
        ajax: {
            url: "{{ route('foda-perfiles.index') }}",
            data: function(d) { d.type = 'individual'; }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'context', name: 'context' },
            { data: 'dependency', name: 'dependency' },
            { data: 'model', name: 'model' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        drawCallback: function(s) {
            $('#count-individual').text(s.fnRecordsTotal());
        }
    });

    // Redibujar al cambiar de tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if ($(e.target).attr('href') === '#panel-individual') {
            tableIndividual.columns.adjust().draw();
        } else {
            tableGrupal.columns.adjust().draw();
        }
    });

    // ── helpers Select2 ──────────────────────────────────────
    function initializeSelect2(selector, placeholder, url) {
        selector.select2({
            placeholder: placeholder,
            ajax: {
                url: url, dataType: 'json', delay: 250,
                processResults: function(data) {
                    return { results: $.map(data, function(i) {
                        return { id: i.id, text: i.name || i.dependency };
                    })};
                }, cache: true
            }
        });
    }

    function initValueSelect2(control, key, value) {
        control.empty().append(new Option(value, key, true, true)).trigger('change');
    }

    function toggleType(typeValue, data) {
        if (typeValue === 'grupal') {
            $('.form-group.groups, .form-group.group_roots').show();
            $('.form-group.dependencies, .form-group.dependencies_roots').hide();
            if (data) {
                initValueSelect2($('#group_roots'), data.rootGroup.id, data.rootGroup.name);
                $('#group_roots').select2({ placeholder: 'Seleccione Evento / Grupo Raíz',
                    ajax: { url: '{{ route('globales.get-root-groups') }}', dataType: 'json', delay: 250,
                        processResults: function(d) { return { results: $.map(d, function(i) { return { id: i.id, text: i.name }; }) }; }
                    }
                });
                initValueSelect2($('#groups'), data.profile.group.id, data.profile.group.name);
                $('#groups').select2({ placeholder: 'Seleccione el Grupo',
                    ajax: { url: 'admin/globales/get-groups/' + data.rootGroup.id, dataType: 'json', delay: 250,
                        processResults: function(d) { return { results: $.map(d, function(i) { return { id: i.id, text: i.name }; }) }; }
                    }
                });
                $('#group_roots').off('change.sub').on('change.sub', function() {
                    initializeSelect2($('#groups'), 'Seleccione el Grupo', 'admin/globales/get-groups/' + $(this).val());
                    $('#groups').val(null).trigger('change');
                });
            } else {
                initializeSelect2($('#group_roots'), 'Seleccione Evento / Grupo Raíz', '{{ route('globales.get-root-groups') }}');
                $('#group_roots').off('change.sub').on('change.sub', function() {
                    initializeSelect2($('#groups'), 'Seleccione el Grupo', 'admin/globales/get-groups/' + $(this).val());
                    $('#groups').val(null).trigger('change');
                });
                $('#groups').select2({ placeholder: 'Seleccione el Grupo' });
            }
        } else {
            $('.form-group.groups, .form-group.group_roots').hide();
            $('.form-group.dependencies, .form-group.dependencies_roots').show();
            if (data && data.profile.dependency) {
                initValueSelect2($('#dependencies_roots'), data.rootDependency.id, data.rootDependency.dependency);
                initValueSelect2($('#dependency'), data.profile.dependency.id, data.profile.dependency.dependency);
            }
            initializeSelect2($('#dependencies_roots'), 'Seleccione Dependencia Raíz', '{{ route('globales.get-dependencies-root') }}');
            $('#dependencies_roots').off('change.dep').on('change.dep', function() {
                initializeSelect2($('#dependency'), 'Seleccione la Dependencia', 'admin/globales/get-dependencies/' + $(this).val());
            });
        }
    }

    // ── Nuevo Perfil ─────────────────────────────────────────
    $('#createNewProfile').click(function() {
        $('#profileForm').trigger('reset');
        $('#saveBtn').val('create');
        $('#profile_id').val('');
        $('#modalProfilelHeading').html('Nuevo Perfil');
        $('#modalProfile').modal('show');
        $('#type').select2({ placeholder: 'Seleccione Tipo' });
        $('#type').val('').trigger('change');
        toggleType('individual', null);
        initializeSelect2($('#models'), 'Seleccione el Modelo', '{{ route('get-models') }}');
        $('#type').off('change.new').on('change.new', function() {
            toggleType($(this).val(), null);
        });
    });

    // ── Editar Perfil ─────────────────────────────────────────
    $('body').on('click', '.editProfile', function() {
        var profileId = $(this).data('id');
        $.get("{{ route('foda-perfiles.index') }}" + '/' + profileId + '/edit', function(data) {
            $('#modalProfilelHeading').html('Editar Perfil');
            $('#saveBtn').val('edit-profile');
            $('#modalProfile').modal('show');
            $('#profileForm').trigger('reset');
            $('#profile_id').val(data.profile.id);
            $('#name').val(data.profile.name);
            $('#context').val(data.profile.context);
            $('#type').select2({ placeholder: 'Seleccione Tipo' });
            $('#type').val(data.profile.type).trigger('change');
            toggleType(data.profile.type, data);
            initializeSelect2($('#models'), 'Seleccione el Modelo', '{{ route('get-models') }}');
            initValueSelect2($('#models'), data.profile.model_id, data.profile.model.name);
            $('#type').off('change.edit').on('change.edit', function() {
                toggleType($(this).val(), null);
            });
        });
    });

    // ── Guardar ───────────────────────────────────────────────
    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('Enviando...');
        $.ajax({
            data: $('#profileForm').serialize(),
            url: "{{ route('foda-perfiles.store') }}",
            type: 'POST', dataType: 'json',
            success: function(data) {
                $('.success').text(data.success).addClass('alert alert-success');
                setTimeout(function() { $('.success').hide().html(''); }, 4000);
                $('#profileForm').trigger('reset');
                $('#modalProfile').modal('hide');
                $('.success').removeAttr('style');
                tableGrupal.draw();
                tableIndividual.draw();
                $('#saveBtn').html('Guardar');
            },
            error: function(data) {
                $.each(data.responseJSON.errors, function(k, v) {
                    toastr.options = { closeButton: true, progressBar: true };
                    toastr.error('Atención: ' + v);
                });
                $('#saveBtn').html('Guardar');
            }
        });
    });

    // ── Eliminar ──────────────────────────────────────────────
    $('body').on('click', '.deleteProfile', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?', text: 'No podrás revertirlo.',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('foda-perfiles.store') }}" + '/' + id,
                    success: function() {
                        tableGrupal.draw();
                        tableIndividual.draw();
                        Swal.fire('Eliminado', 'El perfil fue eliminado.', 'success');
                    },
                    error: function() { toastr.error('Error al eliminar.'); }
                });
            }
        });
    });
});
</script>
@stop
