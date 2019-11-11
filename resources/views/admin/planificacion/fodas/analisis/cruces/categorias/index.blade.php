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
                        <h4 class="card-title ">Listado de Categorias</h4>
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('foda-categorias.create') }}"> Crear Nueva Categoria</a>
                        @endcan
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('fodas-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'foda-categorias.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','perfil'=>'search']) !!}
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
                                        <th>Ambiente</th>
                                        <th width="350px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categorias as $key => $categoria)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $categoria->nombre }}</td>
                                        <td>{{ $categoria->ambiente }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('foda-categorias.edit',$categoria->id) }}">Editar</a>
                                            {!! Form::open(['route' => ['foda-categorias.destroy', $categoria->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Estas seguro de eliminar el rol {{$categoria->nombre}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                Eliminar
                                            </button>
                                            {!! Form::close() !!}
                                            <a class="btn btn-sm btn-info" href="{{ route('foda-categoria-aspectos',$categoria->id) }}">Aspectos</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" style="text-align: center;">
                        {!! $categorias->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection