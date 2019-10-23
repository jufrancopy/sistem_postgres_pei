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
                        <h4 class="card-title ">Evaluaciones</h4>
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('monitoreo-tipo_evaluaciones.create') }}">Nueva Evalución</a>
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
                                    {!! Form::open(['route' => 'monitoreo-evaluaciones.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','nombre'=>'search']) !!}
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
                                        <th>Item</th>
                                        <th>Tipo</th>
                                        <th>Beneficiado</th>
                                        <th>Condicion</th>
                                        <th>Total</th>
                                        <th>Responsable</th>
                                        <th width="350px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($evaluaciones as $evaluacion)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $evaluacion->item }}</td>
                                        <td>{{ $evaluacion->tipo_evaluacion->nombre }}</td>
                                        <td>{{ $evaluacion->beneficiado }}</td>
                                        <td>{{ $evaluacion->condicion }}</td>
                                        <td>{{ $evaluacion->total }}</td>
                                        <td>{{ $evaluacion->nombre }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-circle" href="{{ route('monitoreo-evaluaciones.edit',$evaluacion->id) }}"><i class="far fa-edit"></i></a>
                                            
                                            {!! Form::open(['route' => ['monitoreo-evaluaciones.destroy', $evaluacion->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar el modelo {{$evaluacion->nombre}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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
                        {!! $evaluaciones->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection