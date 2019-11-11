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
                        <h4 class="card-title ">Perfiles</h4>
                       
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('planificacion-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'foda-listado-perfiles','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','perfil'=>'search']) !!}
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
                                        <th>Contexto</th>
                                        <th colspan=2>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perfiles as $key => $perfil)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $perfil->nombre }}</td>
                                        <td>{{ $perfil->contexto }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-success" href="{{ route('foda-analisis-ambientes',$perfil->id) }}">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="{{ route('foda-analisis-matriz',$perfil->id) }}">
                                                <i class="fa fa-book" aria-hidden="true"></i>
                                            </a>
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