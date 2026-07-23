@extends('layouts.master')
@section('title', 'Encuestas y Evaluaciones')

@section('content')
<div class="card shadow-sm">
    <div class="card-header card-header-info py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <i class="fa fa-poll mr-2"></i>Encuestas y Evaluaciones
                </h4>
                <p class="card-category mb-0 text-muted">
                    Gestión de encuestas grupales e institucionales
                </p>
            </div>
            <button class="btn btn-success" id="createNewProfile">
                <i class="fa fa-plus mr-2"></i>Nueva Encuesta
            </button>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="bg-white rounded-0 p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('globales.dashboard') }}" class="text-decoration-none">Globales</a></li>
            <li class="breadcrumb-item active">Encuestas</li>
        </ol>
    </nav>

    <div class="card-body">
        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-3">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Encuestas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalEncuestas }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-primary shadow h-100 py-3">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Preguntas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalPreguntas }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-3">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Participantes</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalParticipantes }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-success shadow h-100 py-3">
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
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fa fa-clock mr-2 text-primary"></i>Encuestas Recientes
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($encuestasRecientes as $s)
                            <a href="{{ route('surveys.show', $s->id) }}" class="list-group-item list-group-item-action py-3 text-decoration-none">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold" style="font-size:.9rem">
                                            {{ $s->name }}
                                            @if($s->type === 'corporative')
                                                <span class="badge badge-primary ml-2">Corporativo</span>
                                            @else
                                                <span class="badge badge-info ml-2">Grupal</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            <i class="fa fa-question-circle mr-1"></i>{{ $s->questions->count() }} preguntas
                                            @if($s->group)
                                            · <i class="fa fa-users mr-1"></i>{{ $s->group->name }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="ml-2 flex-shrink-0">
                                        <a href="{{ route('surveys.show.details', $s->id) }}" class="btn btn-info btn-sm" title="Resultados">
                                            <i class="fa fa-chart-bar"></i>
                                        </a>
                                    </div>
                                </div>
                            </a>
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
                    <div class="card-header py-3">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fa fa-th mr-2 text-primary"></i>Acciones Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <button id="createNewProfile" class="btn btn-success btn-block py-3 shadow-sm">
                                    <i class="fa fa-plus fa-2x d-block mb-2 text-white"></i>
                                    <span style="font-size:.85rem" class="font-weight-bold">Nueva Encuesta</span>
                                </button>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('surveys.index') }}" class="btn btn-info btn-block py-3 shadow-sm">
                                    <i class="fa fa-list fa-2x d-block mb-2 text-white"></i>
                                    <span style="font-size:.85rem" class="font-weight-bold">Ver Todas</span>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('questions.index') }}" class="btn btn-primary btn-block py-3 shadow-sm">
                                    <i class="fa fa-question-circle fa-2x d-block mb-2 text-white"></i>
                                    <span style="font-size:.85rem" class="font-weight-bold">Banco de Preguntas</span>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('globales.groups.index') }}" class="btn btn-warning btn-block py-3 shadow-sm">
                                    <i class="fa fa-users fa-2x d-block mb-2 text-white"></i>
                                    <span style="font-size:.85rem" class="font-weight-bold">Grupos de Trabajo</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── DataTable completo ── --}}
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fa fa-table mr-2 text-primary"></i>Listado de Encuestas
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover data-table" id="data-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Nombre</th>
                                <th class="text-center" style="width: 100px;">Tipo</th>
                                <th class="text-center" style="width: 100px;">Preguntas</th>
                                <th class="text-center" style="width: 100px;">Participantes</th>
                                <th class="text-center" style="width: 100px;">Completados</th>
                                <th>Analista</th>
                                <th>Grupo</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
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
        <div class="modal-content shadow">
            <div class="modal-header card-header-info">
                <h4 class="modal-title" id="modalHeading">Nueva Encuesta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="surveyForm" name="surveyForm" class="form-horizontal">
                    {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Nombre:</label>
                                {{ Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'required', 'placeholder' => 'Ej: Evaluación de Servicios']) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Tipo:</label>
                                {!! Form::select('type_survey', ['group' => 'Grupal', 'corporative' => 'Corporativo'], null, [
                                    'placeholder' => '', 'id' => 'type_survey', 'style' => 'width:100%',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group dependencies" style="display:none">
                        <label class="form-label font-weight-bold">Elija Corporación:</label>
                        {!! Form::select('dependency_id', [], null, ['placeholder'=>'','id'=>'dependencies','style'=>'width:100%']) !!}
                    </div>

                    <div class="form-group group_roots">
                        <label class="form-label font-weight-bold">Evento:</label>
                        {!! Form::select('group_root_id', [], null, ['placeholder'=>'','id'=>'group_roots','style'=>'width:100%']) !!}
                    </div>

                    <div class="form-group groups">
                        <label class="form-label font-weight-bold">Asignar Grupo de Trabajo:</label>
                        {!! Form::select('group_id', [], null, ['id'=>'groups','placeholder'=>'','style'=>'width:100%']) !!}
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Descripción:</label>
                        {{ Form::textarea('description', null, ['class'=>'form-control editor','id'=>'description','rows'=>3, 'placeholder' => 'Descripción breve de la encuesta...']) }}
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-bold">Analista:</label>
                        {!! Form::select('analyst_id[]', [], null, ['id'=>'analysts','style'=>'width:100%','multiple', 'placeholder' => 'Seleccionar analistas...']) !!}
                    </div>

                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i>Cerrar
                        </button>
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
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'name',        name: 'name' },
            { data: 'type',        name: 'type', className: 'text-center' },
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
