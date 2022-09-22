@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-info">
                    <p>{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title ">Listado de Servicios</h4>
                    <a class="btn btn-success" href="{{ route('globales.servicios.create') }}">Nuevo Servicio</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('globales.dashboard') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <!-- AquiBuscador -->
                            <div class="float-right">
                                {!! Form::open(['route' => 'globales.servicios.index','method' => 'GET',
                                'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                <div class="form-group">
                                    {!! Form::text('name',null, ['class'=>'form-control','placeholder'=>'Buscar
                                    Servicio']) !!}
                                </div>
                                <button type="submit" class="btn btn-default pull-right">Buscar</button>
                            </div>

                            {!!Form::close()!!}
                            <!-- Fin Buscador -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nombre</th>
                                    <th>Tipo de Servicio</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servicios as $key => $servicio)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $servicio->name }}</td>
                                    @switch($servicio->type)
                                    @case($servicio->type == 'final')
                                    <td>Final</td>
                                    @break

                                    @case($servicio->type == 'de_apoyo')
                                    <td>De Apoyo</td>
                                    @break

                                    @default
                                    <span>Sin Tipo</span>
                                    @endswitch
                                    <td>
                                        <a class="btn btn-info btn-circle"
                                            href="{{ route('globales.servicios.edit',$servicio->id) }}"><i
                                                class="far fa-edit"></i>
                                        </a>

                                        {!! Form::open(['route' => ['globales.servicios.destroy', $servicio->id],
                                        'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                        <button class="btn btn-danger btn-circle"
                                            onclick="return confirm('Estas seguro de eliminar el rol {{$servicio->dependency}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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
                    {!! $servicios->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection