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
                            <h4 class="card-title ">Módulos de Análisis FODA - Categorías (Factores)</h4>
                        </div>
                        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('foda-perfiles.index') }}">Planificación-Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('foda-perfiles.index') }}">Perfiles Foda</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('foda-analisis-ambientes', $idPerfil) }}">Ambientes</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Ambiente Interno</li>
                            </ol>
                        </nav>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">

                                    <!-- AquiBuscador -->
                                    <div class="float-right">

                                        {!! Form::open([
                                            'route' => ['foda-analisis-ambiente-interno', $idPerfil],
                                            'method' => 'GET',
                                            'class' => 'navbar-form navbar-left pull-right',
                                            'role' => 'search',
                                        ]) !!}
                                        <div class="form-group">
                                            {!! Form::text('nombre', null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Buscar
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Categoria',
                                            ]) !!}
                                        </div>
                                        <button type="submit" class="btn btn-default pull-right">Buscar</button>
                                    </div>

                                    {!! Form::close() !!}
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
                                                        class="btn btn-info btn-circle">{{ App\Admin\Planificacion\Foda\FodaAspecto::where('categoria_id', $categoria->id)->count() }}</span>
                                                </td>
                                                <td><a
                                                        href="{{ route('foda-analisis-listado-categoria-aspectos', ['idCategoria' => $categoria->id, 'idPerfil' => $idPerfil]) }}">Analizar
                                                        Aspectos</a>
                                                </td>
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
