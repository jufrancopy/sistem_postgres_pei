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
                        <h4 class="card-title ">Listado de Aspectos</h4>
                        @can('foda-create')
                        <a class="btn btn-success" href="{{ route('foda-aspectos.create') }}"> Crear Nuevo Aspecto</a>
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
                                    {!! Form::open(['route' => 'foda-aspectos.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','perfil'=>'search']) !!}
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
                                        <th>Categoria</th>
                                        <th>Ambiente</th>
                                        <th width="280px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aspectos as $key => $aspecto)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $aspecto->nombre }}</td>
                                        <td>{{ $aspecto->categoria->nombre }}</td>
                                        <td>{{ $aspecto->categoria->ambiente }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('foda-aspectos.edit',$aspecto->id) }}">Editar</a>
                                            {!! Form::open(['route' => ['foda-aspectos.destroy', $aspecto->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Estas seguro de eliminar el rol {{$aspecto->nombre}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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
                        {!! $aspectos->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection