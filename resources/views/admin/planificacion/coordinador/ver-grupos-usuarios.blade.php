@extends('layouts.master')

@section('title', 'Ver Grupos y Usuarios')

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title">Ver Grupos y Usuarios</h4>
                        <a href="{{ route('coordinador.index') }}" class="btn btn-sm btn-secondary">Volver al Dashboard</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> 
                                    <strong>Información:</strong> Aquí puede ver todos los grupos y usuarios del grupo padre.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-success">
                                        <h4 class="card-title">Grupo Padre: {{ $grupoPadre->name }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Total de Grupos Hijos:</strong> {{ $gruposHijos->count() }}</p>
                                        <p><strong>Total de Usuarios:</strong> {{ $usuarios->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($gruposHijos as $grupo)
                                                    <tr>
                                                        <td>{{ $grupo->name }}</td>
                                                        <td>{{ $grupo->members->count() }} usuarios</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Todos los Usuarios</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Email</th>
                                                        <th>Grupo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($usuarios as $usuario)
                                                    <tr>
                                                        <td>{{ $usuario->name }}</td>
                                                        <td>{{ $usuario->email }}</td>
                                                        <td>{{ $usuario->group->name ?? 'Sin grupo' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
