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
                        <h4 class="card-title ">{{$pei->nivel}} </h4>
                        <a class="btn btn-success" href="{{ route('peis-crear-sub-nivel',[$nivelSuperior, $id]) }}">Agregar sub Niveles</a>
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('peis.index') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                    <h3><label for="">Misión: </label>{{$pei->vision}}</h3>
                    <h3><label for="">Visión:</label>{{$pei->mision}}</h3>
                    <h3><label for="">Valores</label> {{$pei->valores}}</h3>
                        </ul>
                        <ul>
                            @foreach ($niveles as $nivel)
                            <li>{{ $nivel->nivel }}
                                <a class="btn btn-success btn-circle" href="{{ route('peis-crear-sub-nivel',[$nivelSuperior,$nivel->id]) }}"><i class="fa fa-plus"></i></a>
                                <a class="btn btn-info btn-circle" href="{{ route('peis-editar-sub-nivel',$nivel->id) }}"><i class="fa fa-edit"></i></a>
                                {!! Form::open(['route' => ['peis-eliminar-nivel', $nivelSuperior, $nivel->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                    <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar la Dependencia {{$nivel->nivel}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                {!! Form::close() !!}
                            </li>
                            <ul>
                                @foreach ( $nivel->dependencies as $dependencia )
                                        <p class="badge badge-warning"> ({{$dependencia->dependency}})</p>
                                @endforeach
                            </ul>
                            
                            <hr>
                            <ul>
                                @foreach ($nivel->childrenNiveles as $childNivel)
                                    @include('admin.planificacion.peis.peis.child_nivel', ['child_nivel' => $childNivel])
                                @endforeach
                            </ul>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection