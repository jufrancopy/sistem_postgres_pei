@extends('layouts.master')
@section('title', 'Encuestas y Evaluaciones')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-poll mr-2"></i>Encuestas y Evaluaciones</h4>
        <p class="card-category">Gestión de encuestas grupales e institucionales</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}">Globales</a></li>
            <li class="breadcrumb-item active">Encuestas</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Encuestas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalEncuestas }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Preguntas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalPreguntas }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Participantes</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalParticipantes }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completadas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalCompletadas }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">

            {{-- ── Encuestas recientes ── --}}
            <div class="col-md-5 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-clock mr-1"></i> Encuestas Recientes</h6>
                        <button class="btn btn-success btn-sm" id="createNewProfile">
                            <i class="fa fa-plus mr-1"></i> Nueva
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($encuestasRecientes as $s)
                            <div class="list-group-item py-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold" style="font-size:.88rem">{{ $s->name }}</div>
                                        <small class="text-muted">
                                            <i class="fa fa-question-circle mr-1"></i>{{ $s->questions->count() }} preguntas
                                            @if($s->group)
                                            · <i class="fa fa-users mr-1"></i>{{ $s->group->name }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="ml-2 flex-shrink-0">
                                        <a href="{{ route('surveys.show', $s->id) }}" class="btn btn-warning btn-circle btn-sm" title="Gestionar">
                                            <i class="fa fa-list-ol"></i>
                                        </a>
                                        <a href="{{ route('surveys.show.details', $s->id) }}" class="btn btn-info btn-circle btn-sm ml-1" title="Resultados">
                                            <i class="fa fa-chart-bar"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item text-center text-muted py-4">
                                <em>Sin encuestas registradas</em>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Accesos rápidos ── --}}
            <div class="col-md-7 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-th mr-1"></i> Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <button id="createNewProfile" class="btn btn-success btn-block py-3">
                                    <i class="fa fa-plus fa-2x d-block mb-1"></i>
                                    <span style="font-size:.85rem">Nueva Encuesta</span>
                                </button>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('surveys.index') }}" class="btn btn-info btn-block py-3">
                                    <i class="fa fa-list fa-2x d-block mb-1"></i>
                                    <span style="font-size:.85rem">Ver Todas</span>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('questions.index') }}" class="btn btn-primary btn-block py-3">
                                    <i class="fa fa-question-circle fa-2x d-block mb-1"></i>
                                    <span style="font-size:.85rem">Banco de Preguntas</span>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('globales.groups.index') }}" class="btn btn-warning btn-block py-3">
                                    <i class="fa fa-users fa-2x d-block mb-1"></i>
                                    <span style="font-size:.85rem">Grupos de Trabajo</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── DataTable completo ── --}}
        <div class="card shadow">
            <div class="card-header py-2">
                <h6 class="mb-0 font-weight-bold"><i class="fa fa-table mr-1"></i> Listado de Encuestas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover data-table" id="data-table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th class="text-center">Preguntas</th>
                                <th class="text-center">Participantes</th>
                                <th class="text-center">Completados</th>
                                <th>Analista</th>
                                <th>Grupo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Nueva/Editar Encuesta --}}
<div class="modal fade" id="ajaxSurveyModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-header-info">
                <h4 class="modal-title" id="modalHeading">Nueva Encuesta</h4>
            </div>
            <div class="modal-body">
                <form id="surveyForm" name="surveyForm" class="form-horizontal">
                    {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                {{ Form::label('name', 'Nombre:') }}
                                {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group type_survey">
                                {{ Form::label('type_survey', 'Tipo:') }}
                                {!! Form::select('type_survey', ['group' => 'Grupal', 'corporative' => 'Corporativo'], null, [
                                    'placeholder' => '', 'id' => 'type_survey', 'style' => 'width:100%',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group dependencies" style="display:none">
                        {{ Form::label('dependency_id', 'Elija Corporación:') }}
                        {!! Form::select('dependency_id', [], null, ['placeholder'=>'','id'=>'dependencies','style'=>'width:100%']) !!}
                    </div>

                    <div class="form-group group_roots">
                        {{ Form::label('group_root_id', 'Evento:') }}
                        {!! Form::select('group_root_id', [], null, ['placeholder'=>'','id'=>'group_roots','style'=>'width:100%']) !!}
                    </div>

                    <div class="form-group groups">
                        {{ Form::label('groups', 'Asignar Grupo de Trabajo:') }}
                        {!! Form::select('group_id', [], null, ['id'=>'groups','placeholder'=>'','style'=>'width:100%']) !!}
                    </div>

                    <div class="mb-2">
                        {{ Form::label('description', 'Descripción:') }}
                        {{ Form::textarea('description', null, ['class'=>'form-control editor','id'=>'description','rows'=>3]) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('analyst', 'Analista:') }}
                        {!! Form::select('analyst_id[]', [], null, ['id'=>'analysts','style'=>'width:100%','multiple']) !!}
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">
                            <i class="fa fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var table = $('.data-table').DataTable({
        processing: true, serverSide: true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excel', text: '<i class="fa fa-file-excel"></i>', titleAttr: 'Excel' },
            { extend: 'pdf',   text: '<i class="fa fa-file-pdf"></i>',   titleAttr: 'PDF' },
            { extend: 'print', text: '<i class="fa fa-print"></i>',      titleAttr: 'Imprimir' },
        ],
        language: {
            emptyTable: 'No hay encuestas', processing: 'Procesando...', search: 'Buscar:',
            zeroRecords: 'Sin resultados', info: 'Mostrando _START_ a _END_ de _TOTAL_',
            paginate: { next: 'Siguiente', previous: 'Anterior' }
        },
        ajax: "{{ route('surveys.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name',        name: 'name' },
            { data: 'type',        name: 'type' },
            { data: 'preguntas',   name: 'preguntas',    className: 'text-center', orderable: false },
            { data: 'participantes',name:'participantes', className: 'text-center', orderable: false },
            { data: 'completados', name: 'completados',  className: 'text-center', orderable: false },
            { data: 'analysts',    name: 'analysts',     orderable: false },
            { data: 'group',       name: 'group' },
            { data: 'action',      name: 'action',       orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    // Función para inicializar Select2
    function initializeSelect2(selector, placeholder, url) {
        selector.val("").select2({
            placeholder: placeholder,
            ajax: {
                url: url, dataType: 'json', delay: 250,
                processResults: function(data) {
                    return { results: $.map(data, function(item) {
                        return { text: item.name || item.dependency, id: item.id };
                    })};
                }, cache: true
            }
        });
    }

    // Abrir modal nueva encuesta
    $('#createNewProfile').click(function() {
        $('#saveBtn').val("create-survey");
        $('#profile_id').val('');
        $('#surveyForm').trigger("reset");
        $('#modalHeading').html("Nueva Encuesta");
        $('#ajaxSurveyModal').modal('show');
        $('.form-group.dependencies').hide();

        $('#type_survey').select2().change(function() {
            if ($(this).val() === 'corporative') {
                $('.form-group.dependencies').show();
            } else {
                $('.form-group.dependencies').hide();
            }
        });

        initializeSelect2($("#dependencies"), 'Seleccione la dependencia', '{{ route('globales.get-dependencies') }}');
        initializeSelect2($("#group_roots"), 'Seleccione Grupo Raíz', '{{ route('globales.get-root-groups') }}');

        $('#group_roots').on('change', function() {
            var groupRootID = $(this).val();
            initializeSelect2($("#groups"), 'Seleccione el Grupo', 'admin/globales/get-groups/' + groupRootID);
        });

        initializeSelect2($("#analysts"), 'Seleccione Analistas', '{{ route('globales.get-users') }}');
    });

    // Editar encuesta
    $('body').on('click', '.editSurvey', function() {
        var surveyID = $(this).data('id');
        $.get("{{ route('surveys.index') }}/" + surveyID + '/edit', function(data) {
            $('#modalHeading').html("Editar Encuesta");
            $('#saveBtn').val("edit-survey");
            $('#ajaxSurveyModal').modal('show');
            $('#surveyForm').trigger("reset");
            $('#profile_id').val(data.survey.id);
            $('#name').val(data.survey.name);
        });
    });

    // Guardar
    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('<i class="fa fa-spinner fa-spin mr-1"></i>').prop('disabled', true);
        $.ajax({
            data: $('#surveyForm').serialize(),
            url: "{{ route('surveys.store') }}",
            type: "POST", dataType: 'json',
            success: function(data) {
                toastr.success(data.success || 'Guardado correctamente.');
                $('#surveyForm').trigger("reset");
                $('#ajaxSurveyModal').modal('hide');
                table.draw();
                location.reload();
            },
            error: function(xhr) {
                toastr.error('Error al guardar.');
                $('#saveBtn').html('<i class="fa fa-save mr-1"></i> Guardar').prop('disabled', false);
            }
        });
    });

    // Eliminar
    $('body').on('click', '.deleteSurvey', function() {
        var surveyID = $(this).data("id");
        Swal.fire({
            title: '¿Eliminar esta encuesta?',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#dc3545', confirmButtonText: 'Eliminar',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('surveys.store') }}/" + surveyID,
                    success: function() { table.draw(); location.reload(); },
                    error: function() { toastr.error('Error al eliminar.'); }
                });
            }
        });
    });
});
</script>
@stop
