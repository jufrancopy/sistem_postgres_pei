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
                        <h4 class="card-title ">Matriz</h4>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('fodas-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-bordered">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Aspecto</th>
                                        <th>Categoria</th>
                                        <th>Ambiente</th>
                                        <th>Ocurrencia</th>
                                        <th>Impacto</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                    </tr>
                                    @foreach($analisis as $v)
                                    <tr>
                                        <td>{{$v->aspecto->nombre}}</td>
                                        <td>{{$v->aspecto->categoria->nombre}}</td>
                                        <td>{{$v->aspecto->categoria->ambiente}}</td>
                                        <td>{{$v->impacto}}</td>
                                        <td>{{$v->ocurrencia}}</td>
                                        @php 
                                            $total = number_format($v->matriz,2);
                                        @endphp
                                            @switch ($total) 
                                                @case($total >= 0.18)
                                                    <td bgcolor="#1aeb51">Suficiente ({{$total}})</td>
                                                    @break
                                                @case($total <= 0.17)
                                                    <td bgcolor="#eb4034">Insuficiente ({{$total}})</td>
                                                    @break    
                                                @case($total = 0.00)
                                                <td >Pendiente</td>
                                                @break
                                            @endswitch
                                        @switch($v->tipo)
                                            @case('Fortaleza')
                                                <td><p class="badge badge-success">{{$v->tipo}}</p></td>
                                            @break

                                            @case('Oportunidad')
                                                <td><p class="badge badge-success">{{$v->tipo}}</p></td>
                                            @break
                                            @case('Debilidad')
                                                <td><p class="badge badge-danger">{{$v->tipo}}</p></td>
                                            @break
                                            @case('Amenaza')
                                                <td><p class="badge badge-danger">{{$v->tipo}}</p></td>
                                            @break
                                            @default <td><p class="badge badge-default">Pendiente</p></td>
                                            @endswitch
                                    </tr>
                                    @endforeach
                                    </thead>
                                <tbody>

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