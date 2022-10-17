@extends('layouts.master')
@section('content')
    @include('partials.modal')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Listado de Categorias</h4>
                        <a class="btn btn-success" href="{{ route('globales.categories.create') }}">Agregar Categoría</a>
                        <a class="btn btn-warning float-right" href="{{ route('globales.dashboard') }}"> Atras</a>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open([
                                        'route' => 'globales.variables.index',
                                        'method' => 'GET',
                                        'class' => 'navbar-form navbar-left pull-right',
                                        'role' => 'search',
                                    ]) !!}
                                    <div class="form-group">
                                        {!! Form::text('nombre', null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Buscar
                                                                                                                                                                                                    Organigrama',
                                        ]) !!}
                                    </div>
                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>
                                </div>

                                {!! Form::close() !!}
                                <!-- Fin Buscador -->
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Nombre</th>
                                        <th>Realizadas</th>
                                        <th>Pendientes</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr data-id="{{ $category->id }}">
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>
                                                <a class="btn btn-info btn-circle"
                                                    href="{{ route('globales.categories.show', $category->id) }}"><i
                                                        class="far fa-eye"></i>
                                                </a>

                                                <a class="btn btn-info btn-circle"
                                                    href="{{ route('globales.categories.edit', $category->id) }}"><i
                                                        class="far fa-edit"></i>
                                                </a>

                                                <a class="btn btn-danger btn-circle delete-record"
                                                    href="{{ route('globales.categories.destroy', $category->id) }}"><i
                                                        class="far fa-trush"></i>
                                                </a>



                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" style="text-align: center;">
                        {{-- {!! $variables->render() !!} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
