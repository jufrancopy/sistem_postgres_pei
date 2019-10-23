@extends('layouts.master')

@section('content')
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
                        <h4 class="card-title ">Listado de Perfiles de Planificacion Estratégica Institucional</h4>
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('peis-perfiles.create') }}"> Crear Nuevo Perfil</a>
                        @endcan
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('planificacion-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'peis-perfiles.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','perfil'=>'search']) !!}
                                    <div class="form-group">
                                        {!! Form::text('nombre',null, ['class'=>'form-control','placeholder'=>'Buscar']) !!}
                                    </div>

                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>

                                </div>
                                {!!Form::close()!!}
                                <!-- Fin Buscador -->
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Responsable</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Generado por</th>
                                        <th width="280px">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perfiles as $key => $perfil)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $perfil->nombre }}</td>
                                        <td>{{ $perfil->responsable }}</td>
                                        <td>{{ $perfil->vigencia_desde }}</td>
                                        <td>{{ $perfil->vigencia_hasta }}</td>
                                        <td>{{ $perfil->user->name }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-default" href="{{ route('peis-perfiles.edit',$perfil->id) }}"><i class="far fa-edit"></i></a>
                                            {!! Form::open(['route' => ['peis-perfiles.destroy', $perfil->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Estas seguro de eliminar el perfil {{$perfil->nombre}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
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
                        {!! $perfiles->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection