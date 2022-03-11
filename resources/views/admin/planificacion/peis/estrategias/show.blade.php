@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title "><b>{{ $estrategia->estrategia }}</b></h4>
                        </a>
                        <a class="btn btn-success" href="{{ route('peis-add-programas', $estrategia->id) }}"> Crear Programa</a>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('peis-objetivos.show', $estrategia->objetivo_id) }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <Label>Responsables:</Label>
                            @foreach ($estrategia->dependencies as $v)
                            {{$v->dependency}},
                            @endforeach

                        
                        <div class="table-responsive">
                            
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Programa</th>
                                        <th width="350px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programas as $programa)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $programa->programa }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-circle"
                                                href="{{ route('peis-programas.edit',$programa->id) }}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a class="btn btn-success btn-circle"
                                                href="{{ route('peis-programas.show',$programa->id) }}">
                                                <i class="far fa-eye"></i>
                                            </a>

                                            {!! Form::open(['route' => ['peis-programas.destroy', $programa->id], 'method'
                                            => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle"
                                                onclick="return confirm('Estas seguro de eliminar el objetivo {{$programa->programa}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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