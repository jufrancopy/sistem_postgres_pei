@extends('layouts.master')
@section('title', 'Dashboard-Planificación')
@section('content')
    <!-- Contenido Principal -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Panel de Planificación</h4>
                    @hasrole('Administrador')
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('home') }}"> Atras</a>
                        </div>
                    @endhasrole
                </div>

                <div class="card-body">
                    <!-- Inicio Cabecera con iconos -->
                    <div class="row">

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-info card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">faces</i>
                                    </div>
                                    <p class="card-category">Peis</p>
                                    <h3 class="card-title">{{ $totalPeiPerfil }}</h3>
                                </div>

                                <div class="card-footer">
                                    <div class="stats">
                                        @hasanyrole('Administrador')
                                            <i class="material-icons">search</i>
                                            <a href="{{ route('pei-profiles.index') }}">Ver todo</a>
                                        @endhasanyrole
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-info card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">account_balance</i>
                                    </div>
                                    <p class="card-category">Fodas</p>
                                    <h3 class="card-title">{{ $totalFodaPerfil }}</h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        @hasanyrole('Administrador')
                                            <i class="material-icons">search</i>
                                            <a href="{{ route('foda-perfiles.index') }}">Ver todo</a>
                                        @endhasanyrole
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--Fin de Cabecera con iconos -->
                    <p id="tree1"></p>
                </div>
            </div>

        </div>
    </div>

@section('scripts')
    <script>
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

                            {
                                name: '<a href="{{ route('tasks-list-tree') }}">Árbol de Tareas</a>'
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
                                name: '<a href="{{ route('foda-list-groups') }}">Matríz</a>'
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
@endsection
@endsection
