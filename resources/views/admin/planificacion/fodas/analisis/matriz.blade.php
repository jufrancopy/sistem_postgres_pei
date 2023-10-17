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
                        <h3 class="card-title ">Matriz FODA </h3>
                        <h4>{{$perfil->name}}</h4>
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('planificacion-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-bordered">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <h3>Análisis Interno</h3>
                                        </th>
                                        <th>
                                            <h3>Análisis Externo</h3>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <th class="table-danger">Debilidades</th>
                                        <th class="table-danger">Amenazas</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            @foreach($debilidades as $v)
                                            <ul>
                                                <li>{{$v->aspecto->name}}
                                                    @switch($v->tipo)
                                                    @case('Fortaleza')
                                                    <p class="badge badge-success">{{$v->tipo}}</p>
                                                    @break

                                                    @case('Oportunidad')
                                                    <p class="badge badge-success">{{$v->tipo}}</p>
                                                    @break
                                                    @case('Debilidad')
                                                    <p class="badge badge-danger">{{$v->tipo}}</p>
                                                    @break
                                                    @case('Amenaza')
                                                    <p class="badge badge-danger">{{$v->tipo}}</p>
                                                    @break
                                                    @default
                                                    <p class="badge badge-default">Pendiente</p>
                                                </li>
                                                @endswitch
                                            </ul>
                                            @endforeach
                                        </td>

                                        <td>
                                            @foreach($amenazas as $v)
                                            <ul>
                                                <li>{{$v->aspecto->name}}
                                                    @switch($v->tipo)
                                                    @case('Fortaleza')
                                                    <p class="badge badge-success">{{$v->tipo}}</p>
                                                    @break

                                                    @case('Oportunidad')
                                                    <p class="badge badge-success">{{$v->tipo}}</p>
                                                    @break
                                                    @case('Debilidad')
                                                    <p class="badge badge-danger">{{$v->tipo}}</p>
                                                    @break
                                                    @case('Amenaza')
                                                    <p class="badge badge-danger">{{$v->tipo}}</p>
                                                    @break
                                                    @default
                                                    <p class="badge badge-default">Pendiente</p>
                                                </li>
                                                @endswitch
                                            </ul>
                                            @endforeach
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="table-success">Fortalezas</th>
                                        <th class="table-success">Oportunidades</th>
                                    </tr>
                                    <td>
                                        @foreach($fortalezas as $v)
                                        <ul>
                                            <li>{{$v->aspecto->name}}
                                                @switch($v->tipo)
                                                @case('Fortaleza')
                                                <p class="badge badge-success">{{$v->tipo}}</p>
                                                @break

                                                @case('Oportunidad')
                                                <p class="badge badge-success">{{$v->tipo}}</p>
                                                @break
                                                @case('Debilidad')
                                                <p class="badge badge-danger">{{$v->tipo}}</p>
                                                @break
                                                @case('Amenaza')
                                                <p class="badge badge-danger">{{$v->tipo}}</p>
                                                @break
                                                @default
                                                <p class="badge badge-default">Pendiente</p>
                                            </li>
                                            @endswitch
                                        </ul>
                                        @endforeach

                                    <td>
                                        @foreach($oportunidades as $v)
                                        <ul>
                                            <li>{{$v->aspecto->name}}
                                                @switch($v->tipo)
                                                @case('Fortaleza')
                                                <p class="badge badge-success">{{$v->tipo}}</p>
                                                @break

                                                @case('Oportunidad')
                                                <p class="badge badge-success">{{$v->tipo}}</p>
                                                @break
                                                @case('Debilidad')
                                                <p class="badge badge-danger">{{$v->tipo}}</p>
                                                @break
                                                @case('Amenaza')
                                                <p class="badge badge-danger">{{$v->tipo}}</p>
                                                @break
                                                @default
                                                <p class="badge badge-default">Pendiente</p>
                                            </li>
                                            @endswitch

                                        </ul>
                                        @endforeach
                                    </td>
                                    </td>


                                    </tr>
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <a href="{{ route('foda-cruce-ambientes', $idPerfil) }}" class="btn btn-sm btn-info">
                                                Gestionar Cruce de Ambientes
                                            </a>
                                        </td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection