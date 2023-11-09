@extends('layouts.master')
@section('title', 'Árbol Detalles PEI')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Árbol Detalles PEI {{ $profile->first()->name }}</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Módulo de Planificación
                        Estratégica</a></li>
                <li class="breadcrumb-item active" aria-current="page">Árbol Detalles PEI</li>
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
                                        {{ $profile->first()->name }}
                                    </h2>
                                    <div class="col">
                                        <label><i class="fa fa-calendar" aria-hidden="true"></i> Periodo:
                                        </label>
                                        {{ Carbon\Carbon::parse($profile->first()->year_start)->format('Y') }} -
                                        {{ Carbon\Carbon::parse($profile->first()->year_end)->format('Y') }}
                                    </div>

                                    <div class="col">
                                        <label><i class="fa fa-users" aria-hidden="true"></i> Grupos de
                                            Trabajo:
                                        </label><br>
                                        @php
                                            $totalMembers = 0; // Inicializa el contador de miembros
                                        @endphp

                                        @foreach ($profile->first()->group->descendants as $group)
                                            <span data-toggle="collapse" href="#group_{{ $group->id }}" role="button"
                                                aria-expanded="false" aria-controls="group_{{ $group->id }}"
                                                class="badge badge-secondary">{{ $group->name }}
                                            </span>
                                            <div class="collapse" id="group_{{ $group->id }}">
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
                                                                @foreach ($group->members as $index => $member)
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
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="height: 16rem;">
                                <div class="card-header">
                                    <h6>Actividades por Gerencia</h6>
                                </div>
                                <div id="piechart" style="width: 200; height: 200;"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h6>Resumen de Tareas</h6>
                        </div>
                        <div class="card-body">
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

                        </div>
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

            // Gráficos
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Actions Count'], // Encabezados de la tabla
                    @foreach ($responsiblesActionsCount as $responsibleId => $actionsCount)
                        @php
                            $responsible = App\Admin\Globales\Organigrama::find($responsibleId);
                        @endphp
                            ['{{ $responsible->dependency }}', {{ $actionsCount }}], // Cada fila de datos
                    @endforeach
                ]);

                var options = {
                    title: 'Actividades por Dependencia'
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(data, options);
            }


            var data = [
                @foreach ($profile as $matriz)
                    {
                        name: '{!! $matriz->name !!}',
                        children: [
                            @foreach ($matriz->children->sortBy('order_item') as $axi)
                                {
                                    name: '<p class="badge badge-success">EJE</p> {!! $axi->name !!}',
                                    children: [
                                        @foreach ($axi->children->sortBy('order_item') as $goal)
                                            {
                                                name: '<sup class="badge badge-primary">Objetivo</sup> {!! $goal->name !!}',
                                                children: [

                                                    {
                                                        name: '<div class="card">' +
                                                            '<div class="card-header bg-info">' +
                                                            '<h6 class="text-white">Lista de acciones de {!! $goal->name !!}</h6>' +
                                                            '</div>' +
                                                            '<div class="card-body">' +
                                                            @foreach ($goal->children->sortBy('order_item') as $action)
                                                                '<table class="table table-responsive">' +
                                                                '<tr>' +
                                                                '<th>Nro.</th>' +
                                                                '<th>Acción</th>' +
                                                                '<th>Indicador</th>' +
                                                                '<th>Línea de Base</th>' +
                                                                '<th>Meta</th>' +
                                                                '<th>Responsable</th>' +
                                                                '</tr>' +
                                                                '<tr>' +
                                                                '<td>{{ $action->order_item }}</td>' +
                                                                '<td>{!! $action->name !!}</td>' +
                                                                '<td>{!! $action->indicator !!}</td>' +
                                                                '<td>{!! $action->baseline !!}</td>' +
                                                                '<td>{!! $action->target !!}</td>' +
                                                                '<td>' +
                                                                @foreach ($action->responsibles as $responsible)
                                                                    '<span class="badge badge-secondary">{{ $responsible->dependency }}</span> ' +
                                                                @endforeach
                                                                '</td>' +
                                                                '</tr>' +
                                                                '</table>' +
                                                                '<hr>' +
                                                                '<br>' +
                                                            @endforeach
                                                        '</div>' +
                                                        '</div>',
                                                    },

                                                ]
                                            },
                                        @endforeach
                                    ]
                                },
                            @endforeach
                        ]
                    },
                @endforeach
            ];

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
                        $('#ajaxParticipantsListModal').modal('show');

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
        })
    </script>

@stop

