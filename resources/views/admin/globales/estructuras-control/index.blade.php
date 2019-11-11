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
                        <h4 class="card-title ">Estructuras de Control</h4>

                        <a class="btn btn-success" href="{{ route('estructuras-control.create') }}">Nueva Estructura de Control</a>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('globales-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'estructuras-control.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                    <div class="form-group">
                                        {!! Form::text('esctructura',null, ['class'=>'form-control','placeholder'=>'Buscar Estructura']) !!}
                                    </div>

                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>

                                </div>

                                {!!Form::close()!!}
                                <!-- Fin Buscador -->
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Estructura</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estructuras as $estructura)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $estructura->subDependencia->dependency }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-circle" href="{{ route('estructuras-control.edit',$estructura->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            <a class="btn btn-info btn-circle" href="{{ route('estructuras-control.show',$estructura->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            {!! Form::open(['route' => ['estructuras-control.destroy', $estructura->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar el item {{$estructura->dependencia}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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
                        {!! $estructuras->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection