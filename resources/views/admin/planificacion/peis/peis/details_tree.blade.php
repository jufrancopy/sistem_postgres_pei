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
