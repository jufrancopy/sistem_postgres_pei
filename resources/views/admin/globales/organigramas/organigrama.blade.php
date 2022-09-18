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
                        <h4 class="card-title ">{{$dependencia->dependency}}</h4>
                        <a class="btn btn-success"
                            href="{{ route('globales.organigramas-crear-subdependencia',$dependencia->id) }}">Agregar
                            Dependencia</a>
                        <a class="btn btn-warning" href="{{ route('globales.organigramas.show',$dependencia->id) }}">Ver
                            Organigrama</a>
                        <a class="btn btn-secondary" href="{{ route('globales.organigramas.edit',$dependencia->id) }}">Editar</a>
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('globales.organigramas.index') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul>
                            @foreach ($dependencia->children as $depth1)
                            <li>{{ $depth1->dependency }}
                                <a class="btn btn-success btn-circle"
                                    href="{{ route('globales.organigramas-crear-subdependencia',$depth1->id) }}"><i
                                        class="fa fa-plus"></i></a>
                                <a class="btn btn-info btn-circle"
                                    href="{{ route('globales.organigramas-editar-subdependencia',$depth1->id) }}"><i
                                        class="fa fa-edit"></i></a>
                                {!! Form::open(['route' => ['globales.organigramas.destroy', $depth1->id], 'method' =>
                                'DELETE', 'style'=>'display:inline']) !!}
                                <button class="btn btn-danger btn-circle"
                                    onclick="return confirm('Estas seguro de eliminar la Dependencia {{$depth1->dependency}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                    <i class="fa fa-trash"></i>
                                </button>
                                {!! Form::close() !!}
                            </li>
                            <ul>
                                @foreach ($depth1->children as $childDependency)
                                @include('admin.globales.organigramas.child_dependency', ['child_dependency' =>
                                $childDependency])
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