@extends('layouts.master')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title "><b>{{ $perfil->nombre }}</b></h4>
                            <a class="btn btn-success" href="{{ route('peis-add-objetivos', $perfil->id) }}">Agregar
                                Objetivos</a>
                            <div class="pull-right">
                                <a class="btn btn-warning pull-right" href="{{ route('peis-perfiles.index') }}"> Atras</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Nombre:</label> {{ $perfil->nombre }}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Responsable:</label> {{ $perfil->responsable }}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Misión:</label>
                                    {{ $perfil->mision }}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Visión:</label>
                                    {{ $perfil->vision }}
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Valores:</label>
                                    {{ $perfil->valores }}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Obejtivo</th>
                                            <th width="350px">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($objetivos as $objetivo)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $objetivo->objetivo }}</td>
                                                <td>
                                                    <a class="btn btn-primary btn-circle"
                                                        href="{{ route('peis-objetivos.edit', $objetivo->id) }}">
                                                        <i class="far fa-edit"></i>
                                                    </a>

                                                    <a class="btn btn-success btn-circle"
                                                        href="{{ route('peis-objetivos.show', $objetivo->id) }}">
                                                        <i class="far fa-eye"></i>
                                                    </a>

                                                    {!! Form::open([
                                                        'route' => ['peis-objetivos.destroy', $objetivo->id],
                                                        'method' => 'DELETE',
                                                        'style' => 'display:inline',
                                                    ]) !!}
                                                    <button class="btn btn-danger btn-circle"
                                                        onclick="return confirm('Estas seguro de eliminar el objetivo {{ $objetivo->objetivo }}. Si lo eliminas también eliminarás los datos asociados a el.')">
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
    </div>
@endsection
