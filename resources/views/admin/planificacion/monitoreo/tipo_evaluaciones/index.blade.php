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
                        <h4 class="card-title ">Listado de Tipos de Evaluación</h4>
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('monitoreo-tipo_evaluaciones.create') }}"> Crear Nuevo Modelo</a>
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
                                    {!! Form::open(['route' => 'monitoreo-tipo_evaluaciones.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','nombre'=>'search']) !!}
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
                                        <th width="350px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipoevaluaciones as $tipoevaluacion)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $tipoevaluacion->nombre }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-circle" href="{{ route('monitoreo-tipo_evaluaciones.edit',$tipoevaluacion->id) }}"><i class="far fa-edit"></i></a>
                                            
                                            {!! Form::open(['route' => ['monitoreo-tipo_evaluaciones.destroy', $tipoevaluacion->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar el modelo {{$tipoevaluacion->nombre}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                            <i class="fa fa-trash"></i>
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
                        {!! $tipoevaluaciones->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection