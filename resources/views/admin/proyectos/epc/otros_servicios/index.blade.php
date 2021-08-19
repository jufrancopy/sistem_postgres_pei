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
                    <h4 class="card-title ">Otrs Serivicios</h4>
                    <a class="btn btn-success" href="{{ route('proyectos-epc-otros_servs.create') }}">Agregar</a>
                    <div class="pull-right">
                        <a class="btn btn-warning pull-right" href="{{ route('proyectos-epc-home') }}"> Atras</a>
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
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Costo</th>
                                    <th width="280px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($otrosServicios as $key => $otroServicio)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $otroServicio->item }}</td>

                                    @switch( $otroServicio->type )
                                    @case('limpieza')
                                    <td>Limpieza</td>
                                    @break

                                    @case('seguridad')
                                    <td>Seguridad</td>
                                    @break

                                    @case('gastronomia')
                                    <td>Gastronomía</td>
                                    @break

                                    @case('ambulancia')
                                    <td>Ambulancia</td>
                                    @break

                                    @default
                                    <td>Sin tipo</td>
                                    @endswitch

                                    <td>{{ $otroServicio->cost }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-circle"
                                            href="{{ route('proyectos-epc-otros_servs.edit',$otroServicio->id) }}"><i
                                                class="far fa-edit"></i></a>
                                        </i></a>
                                        {!! Form::open(['route' =>
                                        ['proyectos-epc-otros_servs.destroy',$otroServicio->id], 'method' =>
                                        'DELETE',
                                        'style'=>'display:inline']) !!}
                                        <button class="btn btn-danger btn-circle"
                                            onclick="return confirm('Estas seguro de eliminar {{$otroServicio->item}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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