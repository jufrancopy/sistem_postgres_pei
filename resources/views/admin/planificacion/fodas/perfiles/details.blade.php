@extends('layouts.master')
@section('title', 'Árbol Detalles PEI')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Árbol Detalles de Análisis FODA</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('foda-perfiles.index') }}">Módulo de Análisis FODA</a></li>
                <li class="breadcrumb-item active" aria-current="page">Árbol </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12 detailHeader">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Resumen del Perfíl</h6>
                                </div>
                                <div class="card-header">
                                    <h2>
                                        {{ $fodaProfile->name }}
                                    </h2>


                                    <div class="col">
                                        <label><i class="fa fa-users" aria-hidden="true"></i> Grupos de
                                            Trabajo:
                                        </label><br>
                                        @php
                                            $totalMembers = 0;
                                        @endphp

                                        @if ($fodaProfile->group)
                                            <span data-toggle="collapse" href="#group_{{ $fodaProfile->group->id }}"
                                                role="button" aria-expanded="false"
                                                aria-controls="group_{{ $fodaProfile->group->id }}"
                                                class="badge badge-secondary">{{ $fodaProfile->group->name }}
                                            </span>
                                        @else
                                            <span data-toggle="collapse" href="#group" role="button" aria-expanded="false"
                                                aria-controls="group" class="badge badge-secondary">Pendiente
                                            </span>
                                        @endif
                                        @if ($fodaProfile->group)
                                            <div class="collapse" id="group_{{ $fodaProfile->group->id }}">
                                                <div class="card card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr class="table-success">
                                                                    <th>Nro</th>
                                                                    <th>Participantes</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($fodaProfile->group->members as $index => $member)
                                                                    @php
                                                                        $totalMembers++; // Incrementa el contador de miembros
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td>
                                                                            <span
                                                                                class="badge badge-secondary">{{ $member->name }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="collapse" id="group">
                                                <div class="card card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr class="table-success">
                                                                    <th>Nro</th>
                                                                    <th>Participantes</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- <div class="card" style="height: 16rem;">
                                <div class="card-header">
                                    <h6>Actividades por Gerencia</h6>
                                </div>
                                <div id="piechart" style="width: 200; height: 200;"></div>
                            </div> --}}
                        </div>
                    </div>
                </div>


                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h6>Resumen de Tareas</h6>
                        </div>
                        {{-- <div class="card-body">
                            <div class="row border">
                                <div class="col-md-3 border-right border-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label><i class="fa fa-arrows-h" aria-hidden="true"></i> Ejes: </label>
                                        <div class="float-right">
                                            <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-id="{{ $profile->first()->id }}"
                                                id="showAxisList">
                                                {{ $profile->first()->where('level', 'axi')->count() }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 border-right border-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label><i class="fa fa-bullseye" aria-hidden="true"></i> Objetivos: </label>
                                        <div class="float-right">
                                            <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-id="{{ $profile->first()->id }}"
                                                id="showGoalsList">
                                                {{ $profile->first()->where('level', 'goal')->count() }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 border-right border-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label><i class="fa fa-rocket" aria-hidden="true"></i> Acciones: </label>
                                        <div class="float-right">
                                            <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-id="{{ $profile->first()->id }}"
                                                id="showActionsList">
                                                {{ $profile->first()->where('level', 'action')->count() }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label><i class="fa fa-user" aria-hidden="true"></i> Participantes: </label>
                                        <div class="float-right">
                                            <a class="btn btn-danger btn-circle text-white btn-circle ml-auto"
                                                href="javascript:void(0)" data-id="{{ $profile->first()->id }}"
                                                id="showParticipantsList">
                                                {{ $totalMembers }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> --}}
                    </div>
                </div>
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h6>Tareas</h6>
                            <div class="card-body">
                                <div id="treeProfile">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Inicio Modals --}}
            <div class="modal fade" id="ajaxAxisListlModal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="card-header card-header-info">
                            <h4 class="modal-title" id="modalHeadingAxisList"></h4>
                        </div>

                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                            <div class="table-responsive" id="axisList">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nro.</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="text-center">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ajaxGoalsListModal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="card-header card-header-info">
                            <h4 class="modal-title" id="modalHeadingGoalsList"></h4>
                        </div>

                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                            <div class="table-responsive" id="goalsList">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nro.</th>
                                            <th>Nombre</th>
                                            <th>Estrategias del Cruce de Ambientes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="text-center">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ajaxActionsListModal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="card-header card-header-info">
                            <h4 class="modal-title" id="modalHeadingActionsList"></h4>
                        </div>

                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                            <div class="table-responsive" id="actionsList">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nro.</th>
                                            <th>Acción</th>
                                            <th>Indicador</th>
                                            <th>Línea de Base</th>
                                            <th>Meta</th>
                                            <th>Responsable</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="text-center">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ajaxParticipantsListModal" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="card-header card-header-info">
                            <h4 class="modal-title" id="modalHeadingParticipantsList"></h4>
                        </div>

                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                            <div class="table-responsive" id="participantsList">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nro.</th>
                                            <th>nombre</th>
                                            <th>Correo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="text-center">
                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario Analisis --}}
            <div class="modal fade" id="modalAnalysis" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="card-header card-header-info">
                            <h4 class="modal-title" id="headingAnalysis"></h4>
                        </div>
                        <div class="modal-body">
                            <form id="analysisForm" name="analysisForm" class="form-horizontal">
                                <div class="form-group">
                                    {{ Form::hidden('user_id', auth()->user()->id) }}
                                    {{ Form::hidden('perfil_id', null, ['class' => 'form-control', 'id' => 'perfil_id']) }}
                                    {{ Form::hidden('aspecto_id', null, ['class' => 'form-control', 'id' => 'aspecto_id']) }}
                                    {{ Form::label('tipo', 'Tipo:', ['class' => 'form-control', 'id' => 'tipo']) }}

                                    <div class="form-group">
                                        {{ Form::label('ocurrencia', 'Nivel de Ocurrencia') }}
                                        {{ Form::select(
                                            'ocurrencia',
                                            ['0.10' => 'Baja', '0.30' => 'Media', '0.50' => 'Alta', '0.70' => 'Muy Alta', '0.90' => 'Cierta'],
                                            null,
                                            [
                                                'class' => 'form-control',
                                                'placeholder' => '',
                                                'id' => 'ocurrencia',
                                                'style' => 'width:100%',
                                            ],
                                        ) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('impacto', 'Nivel de Impacto') }}
                                        {{ Form::select(
                                            'impacto',
                                            ['0.05' => 'Muy Bajo', '0.10' => 'Bajo', '0.20' => 'Moderado', '0.40' => 'Alto', '0.80' => 'Muy Alto'],
                                            null,
                                            [
                                                'class' => 'form-control',
                                                'placeholder' => '',
                                                'id' => 'impacto',
                                                'style' => 'width:100%',
                                            ],
                                        ) }}
                                    </div>

                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success" id="saveBtn"
                                            value="create">Guardar cambios</button>
                                    </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Fin Modals --}}
        </div>
    </div>
@stop

@section('scripts')

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Aqui Grafico Google
            var data = {!! json_encode($tree) !!};

            // Función para inicializar Select2
            function initializeSelect2(selector, placeholder, url) {
                selector.val("").select2({
                    placeholder: placeholder,
                    ajax: {
                        url: url,
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.name || item
                                            .dependency, // Use 'name' or 'dependency' depending on the selector
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            $('#treeProfile').tree({
                data: data,
                autoEscape: false,
                saveState: true,
                closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
                openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
                autoOpen: true,
                openFolderDelay: 1000,
                dragAndDrop: true
            });

            $('body').on('click', '#showAxisList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/axis-list',
                    function(data) {
                        $('#modalHeadingAxisList').html(
                            'Lista de Ejes');
                        $('#ajaxAxisListlModal').modal('show');

                        var tableBody = $('#axisList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.axis.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));


                            tableBody.append(newRow);
                        });


                    });
            });

            $('body').on('click', '#showGoalsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/goals-list',
                    function(data) {
                        $('#modalHeadingGoalsList').html(
                            'Lista de Objetivos');
                        $('#ajaxGoalsListModal').modal('show');

                        var tableBody = $('#goalsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.goals.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));

                            // Crear una celda para mostrar todas las estrategias
                            var strategiesCell = $('<td>');

                            row.strategies.forEach(function(strategy) {
                                // Agregar cada estrategia a la celda
                                strategiesCell.append(strategy.estrategia + '<br>');
                            });

                            newRow.append(strategiesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $('body').on('click', '#showActionsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/actions-list',
                    function(data) {
                        $('#modalHeadingActionsList').html(
                            'Lista de Acciones');
                        $('#ajaxActionsListModal').modal('show');

                        var tableBody = $('#actionsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla
                        data.actions.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            newRow.append($('<td>').html(row.indicator));
                            newRow.append($('<td>').html(row.baseline));
                            newRow.append($('<td>').html(row.target));

                            // Crear una celda para mostrar todas las estrategias
                            var responsaiblesCell = $('<td>');

                            row.responsibles.forEach(function(responsible) {
                                // Agregar cada estrategia a la celda
                                responsaiblesCell.append(responsible.dependency +
                                    '<br>');
                            });

                            newRow.append(responsaiblesCell);
                            tableBody.append(newRow);
                        });



                    });
            });

            $('body').on('click', '#showParticipantsList', function() {
                var profileID = $(this).data('id');

                $.get("{{ route('pei-profiles.index') }}" +
                    '/' + profileID + '/members-list',
                    function(data) {
                        $('#modalHeadingParticipantsList').html(
                            'Lista de Participantes');
                        $('#modalAnalysis').modal('show');

                        var tableBody = $('#participantsList .table tbody');
                        tableBody.empty(); // Limpiar el contenido de la tabla

                        // Itera sobre los datos y agrega filas a la tabla

                        data.members.forEach(function(row, index) {
                            var newRow = $('<tr>');
                            newRow.append($('<td>').text(index + 1));
                            newRow.append($('<td>').html(row.name));
                            newRow.append($('<td>').html(row.email));
                            tableBody.append(newRow);
                        });



                    });
            });

            $('body').on('click', '.editAspect', function() {
                event.preventDefault();
                var aspectId = $(this).data('id');
                var environment = $(this).data('environment');

                $.get("{{ route('foda-analisis.index') }}" + '/' + aspectId + '/edit', function(data) {
                    console.log(data)
                    $('#headingAnalysis').html('Analizar');
                    $('#modalAnalysis').modal('show');
                    $('#perfil_id').val(data.perfil_id);
                    $('#aspecto_id').val(data.aspecto_id);
                    $('#tipo').val(data.tipo);

                    //Inicialización de selectores
                    $('#ocurrencia').select2({
                        placeholder: 'Seleccione Nivel de Ocurrencia'
                    });
                    $('#impacto').select2({
                        placeholder: 'Seleccione Nivel de Impacto'
                    });

                    // Modificar el contenido del modal según el ambiente
                    if (environment === 'Interno') {
                        $('#modalAnalysis #headingAnalysis').text('Analizar');
                        $('#modalAnalysis #tipo').empty().append(
                            $('<label>').append(
                                $('<input>').attr({
                                    type: 'radio',
                                    name: 'tipo',
                                    value: 'Fortaleza'
                                }),
                                $('<p>').addClass('badge badge-success').text('Fortaleza')
                            ),
                            $('<label>').append(
                                $('<input>').attr({
                                    type: 'radio',
                                    name: 'tipo',
                                    value: 'Debilidad'
                                }),
                                $('<p>').addClass('badge badge-danger').text('Debilidad')
                            )
                        );
                    } else {
                        $('#modalAnalysis #headingAnalysis').text('Analizar');
                        $('#modalAnalysis #tipo').empty().append(
                            $('<label>').append(
                                $('<input>').attr({
                                    type: 'radio',
                                    name: 'tipo',
                                    value: 'Oportunidad '
                                }),
                                $('<p>').addClass('badge badge-success').text('Oportunidad')
                            ),
                            $('<label>').append(
                                $('<input>').attr({
                                    type: 'radio',
                                    name: 'tipo',
                                    value: 'Amenaza'
                                }),
                                $('<p>').addClass('badge badge-danger').text('Amenaza')
                            )
                        );
                    }


                });

            })
        })
    </script>

@stop
