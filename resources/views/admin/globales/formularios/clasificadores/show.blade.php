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
                        <h4 class="card-title ">Sub-Clasificadores de {{$clasificador->clasificador}} </h4>
                        <a class="btn btn-success" href="{{ route('formulario-clasificadores-crear-subclasificador',$id) }}">Agregar Sub - Clasificador</a>
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('formulario-clasificadores-listado') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul>
                            @foreach ($clasificadores as $clasificador)
                            <li>{{ $clasificador->clasificador }}
                                <a class="badge badge-success" href="{{ route('formulario-clasificadores-crear-subclasificador',$clasificador->id) }}"><i class="fa fa-plus"></i></a>
                                <a class="badge badge-info" href="{{ route('formulario-clasificadores-editar-subclasificador',$clasificador->id) }}"><i class="fa fa-edit"></i></a>
                                {!! Form::open(['route' => ['formulario-clasificadores.destroy', $clasificador->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                    <button class="badge badge-danger" onclick="return confirm('Estas seguro de eliminar el Clasificador {{$clasificador->clasificador}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                {!! Form::close() !!}
                            </li>
                            <ul>
                                @foreach ($clasificador->childrenClasificadores as $childClasificador)
                                    @include('admin.globales.formularios.clasificadores.child_clasificador', ['child_clasificador' => $childClasificador])
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