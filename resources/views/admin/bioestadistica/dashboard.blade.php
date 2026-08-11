@extends('layouts.master')
@section('title', 'Bioestadísticas — Dashboard')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-heartbeat mr-2"></i>Bioestadísticas</h4>
        <p class="card-category">Panel inicial del módulo</p>
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
                            <i class="material-icons">healing</i>
                        </div>
                        <p class="card-category">Módulo</p>
                        <h3 class="card-title">Dashboard</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">info</i>
                            Módulo listo para incorporar indicadores
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
