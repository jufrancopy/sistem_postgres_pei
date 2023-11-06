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
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>
                            {{ $profile->first()->name }}
                        </h2>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><label>Periodo: </label>
                                {{ Carbon\Carbon::parse($profile->first()->year_start)->format('Y') }} -
                                {{ Carbon\Carbon::parse($profile->first()->year_end)->format('Y') }}
                            <li class="list-group-item"><label><i class="fa fa-arrows-h" aria-hidden="true"></i>
                                    Ejes: </label>
                                <div class="btn btn-danger btn-circle">
                                    {{ $profile->first()->where('level', 'axi')->count() }}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <label><i class="fa fa-bullseye" aria-hidden="true"></i>
                                    Objetivos: </label>
                                <div class="btn btn-danger btn-circle">
                                    {{ $profile->first()->where('level', 'goal')->count() }}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <label><i class="fa fa-rocket" aria-hidden="true"></i>
                                    Acciones:</label>
                                <div class="btn btn-danger btn-circle">
                                    {{ $profile->first()->where('level', 'action')->count() }}
                                </div>
                            </li>
                            <li class="list-group-item">

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
                            </li>
                            <li class="list-group-item"><label><i class="fa fa-user" aria-hidden="true"></i> Participantes
                                    Totales:
                                </label>
                                <div class="btn btn-danger btn-circle">{{ $totalMembers }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">
                            <div id="treeProfile">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        })
    </script>

@stop
