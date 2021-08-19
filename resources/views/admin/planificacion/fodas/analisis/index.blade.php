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
                            <a class="btn btn-warning" href="#"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                    <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <!-- AquiBuscador -->
                                    <div class="float-right">
                                        {!! Form::open(['route' => 'users.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                        <div class="form-group">
                                            {!! Form::text('ci',null, ['class'=>'form-control','placeholder'=>'Buscar Usuario']) !!}
                                        </div>
                                        <button type="submit" class="btn btn-default pull-right">Buscar</button>
                                    </div>

                                    {!!Form::close()!!}
                                    <!-- Fin Buscador -->
                                    <thead>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Aspecto</th>
                                            <th width="280px">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aspectos as $aspecto)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $aspecto->nombre }}</td>
                                            <td><a href="{{route('foda-analisis.edit', $analisis->id)}}">Analizar</a></td>
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