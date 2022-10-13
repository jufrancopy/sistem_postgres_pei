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
                    <h4 class="card-title ">Listado de Variables</h4>
                    <a class="btn btn-success" href="{{ route('globales.variables.create') }}">Agregar Variable</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('globales.dashboard') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <!-- AquiBuscador -->
                            <div class="float-right">
                                {!! Form::open(['route' => 'globales.variables.index','method' => 'GET',
                                'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                <div class="form-group">
                                    {!! Form::text('nombre',null, ['class'=>'form-control','placeholder'=>'Buscar
                                    Organigrama']) !!}
                                </div>
                                <button type="submit" class="btn btn-default pull-right">Buscar</button>
                            </div>

                            {!!Form::close()!!}
                            <!-- Fin Buscador -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nombre</th>
                                    <th>Items</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($variables as $key => $variable)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $variable->name }}</td>
                                    <td>
                                        @foreach ($variable->descendants as $item)
                                        <span class="badge badge-secondary">{{$item->name}},</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        {!! Form::open(['route' => ['globales.variables.destroy', $variable->id],
                                        'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                        <button class="btn btn-danger btn-circle"
                                            onclick="return confirm('Estas seguro de eliminar el rol {{$variable->name}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {!! Form::close() !!}
                                        <a class="btn btn-warning btn-circle"
                                            href="{{ route('globales.gestionar-variable', $variable->id) }}"><i
                                                class="fa fa-sitemap" aria-hidden="true"></i></a>
                                        <a class="btn btn-info btn-circle"
                                            href="{{ route('globales.variables.show',$variable->id) }}"><i
                                                class="fa-solid fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer" style="text-align: center;">
                    {!! $variables->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection