@extends('layouts.master')
@section('title', 'Bioestadísticas — Dashboard')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-heartbeat mr-2"></i>Bioestadísticas</h4>
        <p class="card-category">Motor configurable de formularios y estadísticas sanitarias</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Bioestadísticas</li>
        </ol>
    </nav>

    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">dynamic_form</i>
                        </div>
                        <p class="card-category">Formularios</p>
                        <h3 class="card-title">{{ $stats['formularios'] }}</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">check_circle</i>
                            {{ $stats['formularios_activos'] }} activos
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon"><i class="material-icons">place</i></div>
                        <p class="card-category">Establecimientos</p>
                        <h3 class="card-title">{{ $stats['establecimientos'] }}</h3>
                    </div>
                    <div class="card-footer"><div class="stats">{{ $stats['distrito_pendiente'] }} con distrito pendiente</div></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon"><i class="material-icons">list_alt</i></div>
                        <p class="card-category">Catálogos</p>
                        <h3 class="card-title">{{ $stats['catalogos'] }}</h3>
                    </div>
                    <div class="card-footer"><div class="stats">{{ $stats['variables'] }} variables definidas</div></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.formularios.index') }}">Constructor de formularios</a></div>
            <div class="col-md-4"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.catalogos.index') }}">Catálogos y variables</a></div>
            <div class="col-md-4"><a class="btn btn-info btn-block" href="{{ route('bioestadistica.geografia.index') }}">Geografía y establecimientos</a></div>
        </div>
    </div>
</div>
@endsection
