@extends('layouts.master')
@section('content')
<!-- Contenido Principal -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-header-info">
                <h4 class="card-title ">Bienvenidos al Sistema de Planificación del IPS</h4>
                <div class="pull-right">

                </div>
            </div>
            <div class="card-body">
                <!-- Inicio Cabecera con iconos -->
                <div class="row">

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-info card-header-icon">
                                <div class="card-icon">
                                    <i class="material-icons">assignment</i>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <a href="{{route('planificacion-dashboard')}}"><button class="btn btn-success">Planificación</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-danger card-header-icon">
                                <div class="card-icon">
                                    <i class="material-icons">bar_chart</i>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="stats">
                                    <a href="{{route('estadisticas-dashboard')}}"><button class="btn btn-success">Estadisticas</button></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">next_week</i>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <a href="{{route('proyectos-dashboard')}}"><button class="btn btn-success">Proyectos</button></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header-primary card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">settings_applications</i>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <a href="{{route('globales-dashboard')}}"><button class="btn btn-success">Globales</button></a>
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



@endsection