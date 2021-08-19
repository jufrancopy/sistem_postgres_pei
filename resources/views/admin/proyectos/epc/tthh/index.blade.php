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
                    <h4 class="card-title ">Talentos Humanos</h4>
                    <a class="btn btn-success" href="{{ route('proyectos-epc-tthh.create') }}">Agregar</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('proyectos-dashboard') }}"> Atras</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <!-- AquiBuscador -->
                            <div class="float-right">
                                {!! Form::open(['route' => 'organigramas-listado','method' => 'GET',
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
                                    <th>Especialidad</th>
                                    <th>Horas</th>
                                    <th>Costo</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tthhs as $key => $tthh)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $tthh->name }}</td>
                                    <td>{{ $tthh->specialty }}</td>
                                    <td>{{ $tthh->hours }}</td>
                                    <td>{{ $tthh->cost }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-circle"
                                            href="{{ route('proyectos-epc-tthh.edit',$tthh->id) }}"><i class="far fa-edit"></i></a>
                                        </i></a>
                                        {!! Form::open(['route' => ['proyectos-epc-tthh.destroy',$tthh->id], 'method' => 'DELETE',
                                        'style'=>'display:inline']) !!}
                                        <button class="btn btn-danger btn-circle"
                                            onclick="return confirm('Estas seguro de eliminar el rol {{$tthh->name}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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
            </div>
        </div>
    </div>
</div>

@endsection