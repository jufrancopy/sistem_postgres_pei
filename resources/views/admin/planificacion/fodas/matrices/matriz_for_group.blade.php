@extends('layouts.master')
@section('title', 'Matrices Grupales')

@section('content')
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title ">Matrices Grupales</h4>
        </div>
        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Perfiles Matrices </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body">

                            <div id="data">

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

            // Initilizaton JSTree
            $('#data').jstree({
                'core': {
                    'data': {
                        'url': function(node) {
                            var routeDetailItem = "{!! route('tree-group') !!}";


                            return routeDetailItem;
                        },
                        'data': function(node) {
                            return {
                                'id': node.id
                            };
                        }
                    }
                }
            });
        })
        var data = [{
                name: 'Planificación Estratégica Institucional.',
                children: [{
                        name: 'Tareas',
                        children: [{
                                name: '<a href="{{ route('tasks.index') }}">Tareas</a>'
                            },

                            {
                                name: '<a href="{{ route('tasks-type.index') }}">Tipos de Tarea</a>'
                            },
                        ]
                    },
                    {
                        name: 'Perfiles de Planificacion Estrategica',
                        children: [{
                            name: '<a href="{{ route('pei-profiles.index') }}">Pei</a>'
                        }, ]
                    },
                    {
                        name: 'Analisis FODA',
                        children: [{
                                name: '<a href="{{ route('foda-models.index') }}">Modelos</a>'
                            },
                            {
                                name: '<a href="{{ route('foda-perfiles.index') }}">Perfiles</a>'
                            },
                            {
                                name: '<a href="{{ route('foda-listado-perfiles') }}">Cruce de Ambientes</a>'
                            },
                            {
                                name: '<a href="{{ route('foda-matriz-group') }}">Matriz FODA</a>'
                            },
                        ]
                    },
                    {
                        name: 'Analisis de Riesgo',
                        children: [{
                            name: '<a href="{{ route('risks.index') }}">Riesgos</a>'
                        }, ]
                    }
                ]
            },


        ];

        $('#tree1').tree({
            data: data,
            autoEscape: false,
            saveState: true,
            closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
            openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
            autoOpen: true,
            openFolderDelay: 1000,
            dragAndDrop: true
        });
    </script>

@stop
