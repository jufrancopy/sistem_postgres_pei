@extends('layouts.master')

@section('title', 'Coordinador de Planificación')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title">Panel del Coordinador de Planificación</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> 
                                    <strong>Información:</strong> Este panel le permite gestionar los grupos, usuarios y actividades asociados al PEI de su grupo padre.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        <div class="card-icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <p class="card-category">Total de Usuarios</p>
                                        <h3 class="card-title">{{ $usuarios->count() }}</h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="fa fa-clock-o"></i> Usuarios en grupos hijos
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        <div class="card-icon">
                                            <i class="fa fa-sitemap"></i>
                                        </div>
                                        <p class="card-category">Grupos Hijos</p>
                                        <h3 class="card-title">{{ $gruposHijos->count() }}</h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="fa fa-clock-o"></i> Grupos bajo {{ $grupoPadre->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        <div class="card-icon">
                                            <i class="fa fa-tasks"></i>
                                        </div>
                                        <p class="card-category">Actividades</p>
                                        <h3 class="card-title">{{ $actividades->count() }}</h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="fa fa-clock-o"></i> Actividades del PEI
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        <div class="card-icon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                        <p class="card-category">Organigrama</p>
                                        <h3 class="card-title">{{ $organigrama ? 'Sí' : 'No' }}</h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="fa fa-clock-o"></i> Asociado al PEI
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Enlaces de Gestión</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="{{ route('coordinador.gestionar-grupos') }}" class="btn btn-primary btn-block">
                                                    <i class="fa fa-users"></i> Gestionar Grupos y Usuarios
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('coordinador.crear-actividad') }}" class="btn btn-success btn-block">
                                                    <i class="fa fa-plus"></i> Crear Nuevas Actividades
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('coordinador.ver-grupos-usuarios') }}" class="btn btn-info btn-block">
                                                    <i class="fa fa-list"></i> Ver Grupos y Usuarios
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('coordinador.ver-organigrama') }}" class="btn btn-warning btn-block">
                                                    <i class="fa fa-sitemap"></i> Ver Organigrama
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($organigrama)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Organigrama Asociado</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Nombre:</strong> {{ $organigrama->dependency }}</p>
                                        <p><strong>Responsable:</strong> {{ $organigrama->manager }}</p>
                                        <p><strong>Email:</strong> {{ $organigrama->email }}</p>
                                        <p><strong>Teléfono:</strong> {{ $organigrama->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($gruposHijos->count() > 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Grupos Hijos</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Grupo</th>
                                                        <th>Usuarios</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($gruposHijos as $grupo)
                                                    <tr>
                                                        <td>{{ $grupo->name }}</td>
                                                        <td>{{ $grupo->members->count() }} usuarios</td>
                                                        <td>
                                                            <a href="{{ route('coordinador.ver-grupos-usuarios') }}" class="btn btn-sm btn-info">
                                                                <i class="fa fa-eye"></i> Ver
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
