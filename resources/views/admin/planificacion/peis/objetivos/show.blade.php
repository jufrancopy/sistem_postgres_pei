@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title "><b>{{ $objetivo->objetivo }}</b></h4>
                        </a>
                        <a class="btn btn-success" href="{{ route('peis-add-estrategias', $objetivo->id) }}"> Crear Estrategias</a>
                        <div class="pull-right">
                            <a class="btn btn-warning pull-right" href="{{ route('peis-perfiles.show', $objetivo->perfil_id) }}"> Atras</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <Label>Responsables:</Label>
                            @foreach ($objetivo->dependencies as $v)
                            {{$v->dependency}},
                            @endforeach

                        
                        <div class="table-responsive">
                            
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Dependencia</th>
                                        <th width="350px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estrategias as $estrategia)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $estrategia->estrategia }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-circle"
                                                href="{{ route('peis-estrategias.edit',$estrategia->id) }}">
                                                <i class="far fa-edit"></i>
                                            </a>

                                            <a class="btn btn-success btn-circle"
                                                href="{{ route('peis-estrategias.show',$estrategia->id) }}">
                                                <i class="far fa-eye"></i>
                                            </a>

                                            {!! Form::open(['route' => ['peis-estrategias.destroy', $estrategia->id], 'method'
                                            => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle"
                                                onclick="return confirm('Estas seguro de eliminar el objetivo {{$estrategia->estrategia}}. Si lo eliminas también eliminarás los datos asociados a el.')">
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