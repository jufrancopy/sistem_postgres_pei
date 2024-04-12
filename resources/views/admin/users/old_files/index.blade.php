@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Panel de Usuarios</h4>
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('globales.users.create') }}"> Crear nuevo usuario</a> 
                        @endcan
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('globales.dashboard') }}"> Atras</a>
                        </div>
                    </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <!-- AquiBuscador -->
                                    <div class="float-right">
                                        {!! Form::open(['route' => 'globales.users.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                        <div class="form-group">
                                            {!! Form::text('ci',null, ['class'=>'form-control','placeholder'=>'Buscar Usuario']) !!}
                                        </div>

                                        <button type="submit" class="btn btn-default pull-right">Buscar</button>

                                    </div>

                                    {!!Form::close()!!}
                                    <!-- Fin Buscador -->
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Roles</th>
                                            <th width="280px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $user)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if(!empty($user->getRoleNames())) 
                                                @foreach($user->getRoleNames() as $v)
                                                <label class="badge badge-success">{{ $v }}</label> 
                                                @endforeach @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-info" href="{{ route('globales.users.show',$user->id) }}">Ver</a>
                                                <a class="btn btn-sm btn-primary" href="{{ route('globales.users.edit',$user->id) }}">Editar</a> 
                                                

                                                {!! Form::open(['route' => ['globales.users.destroy', $user->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Estas seguro de eliminar a {{$user->name}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                Eliminar
                                                </button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer" style="text-align: center;">
                            {!! $data->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection