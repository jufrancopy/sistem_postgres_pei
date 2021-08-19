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
                        <h4 class="card-title ">Categorias</h4>

                        <a class="btn btn-success" href="{{ route('foda-perfiles.edit', $idPerfil) }}"> Agregar
                            Categorias</a>
                        <a class="btn btn-warning" href="{{ route('foda-analisis-ambientes', $idPerfil) }}"> Cambiar
                            Ambiente</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">

                                <!-- AquiBuscador -->
                                <div class="float-right">

                                    {!! Form::open(['route' => array('foda-analisis-ambiente-interno', $idPerfil),
                                    'method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search'])
                                    !!}
                                    <div class="form-group">
                                        {!! Form::text('nombre',null, ['class'=>'form-control','placeholder'=>'Buscar
                                        Categoria']) !!}
                                    </div>
                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>
                                </div>

                                {!!Form::close()!!}
                                <!-- Fin Buscador -->
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Categorias</th>
                                        <th>Aspectos</th>
                                        <th colspan=2>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categorias as $categoria)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $categoria->nombre }}</td>
                                        <td><span
                                                class="btn btn-info btn-circle">{{App\Admin\Planificacion\Foda\FodaAspecto::where('categoria_id', $categoria->id)->count()}}</span>
                                        </td>
                                        <td><a
                                                href="{{route('foda-analisis-listado-categoria-aspectos', ['idCategoria' => $categoria->id, 'idPerfil' => $idPerfil])}}">Analizar
                                                Aspectos</a></td>

                                    </tr>
                                    @endforeach
                                </tbody>
                                <div class="card-footer" style="text-align: center;">
                                    {!! $categorias->render() !!}
                                </div>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection