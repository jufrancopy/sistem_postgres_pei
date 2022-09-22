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
                        <h4 class="card-title ">{{$variable->name}}
                            @switch($variable->type)
                            @case($variable->type == 'service')
                            <span class="badge badge-danger">Servicio</span>
                            @break

                            @case($variable->type == 'require')
                            <span class="badge badge-warning">Requerimiento</span>
                            @break

                            @case($variable->type == 'item')
                            <span class="badge badge-success">Item</span>
                            @break

                            @default
                            <span>Sin Tipo</span>
                            @endswitch
                        </h4>


                        <a class="btn btn-success"
                            href="{{ route('globales.variables-crear-item',$variable->id) }}">Agregar
                            Item</a>
                        <a class="btn btn-warning" href="{{ route('globales.organigramas.show',$variable->id) }}">Ver
                            Organigrama</a>
                        <a class="btn btn-secondary"
                            href="{{ route('globales.variables.edit',$variable->id) }}">Editar</a>
                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('globales.variables.index') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul>
                            @foreach ($variable->children as $depth1)
                            <li>{{ $depth1->name }}
                                <a class="btn btn-success btn-circle"
                                    href="{{ route('globales.variables-crear-item',$depth1->id) }}"><i
                                        class="fa fa-plus"></i></a>
                                <a class="btn btn-info btn-circle"
                                    href="{{ route('globales.variables-editar-item',$depth1->id) }}"><i
                                        class="fa fa-edit"></i></a>
                                {!! Form::open(['route' => ['globales.variables.destroy', $depth1->id], 'method' =>
                                'DELETE', 'style'=>'display:inline']) !!}
                                <button class="btn btn-danger btn-circle"
                                    onclick="return confirm('Estas seguro de eliminar la Dependencia {{$depth1->name}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                    <i class="fa fa-trash"></i>
                                </button>
                                {!! Form::close() !!}
                                @switch($depth1->type)
                                @case($depth1->type == 'service')
                                <span class="badge badge-danger">Servicio</span>
                                @break

                                @case($depth1->type == 'require')
                                <span class="badge badge-warning">Requerimiento</span>
                                @break

                                @case($depth1->type == 'item')
                                <span class="badge badge-success">Item</span>
                                @break

                                @default
                                <span>Sin Tipo</span>
                                @endswitch
                            </li>
                            <ul>
                                @foreach ($depth1->children as $childItem)
                                @include('admin.globales.formularios.variables.child_item', ['child_item' =>
                                $childItem])
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