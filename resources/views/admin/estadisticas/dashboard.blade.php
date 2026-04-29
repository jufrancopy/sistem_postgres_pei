@extends('layouts.master')
@section('title', 'Dashboard-Estadísticas')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title">Panel de Estadísticas</h4>
                    @hasrole('Administrador')
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('home') }}">Atrás</a>
                        </div>
                    @endhasrole
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-info card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">bar_chart</i>
                                    </div>
                                    <p class="card-category">Módulo</p>
                                    <h3 class="card-title">Estadísticas</h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons">info</i>
                                        En desarrollo
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p id="treeEstadisticas"></p>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        var data = [{
            name: 'Estadísticas',
            children: []
        }];

        $('#treeEstadisticas').tree({
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
