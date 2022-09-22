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
                    <h4 class="card-title ">Ítems de la variable {{$variable->name}}</h4>

                        <a class="btn btn-success" href="{{ route('formulario-agregar-item', $idVariable) }}">Nuevo Ítem</a>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('globales.dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'formulario-items.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                    <div class="form-group">
                                        {!! Form::text('item',null, ['class'=>'form-control','placeholder'=>'Buscar Ítem']) !!}
                                    </div>

                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>

                                </div>

                                {!!Form::close()!!}
                                <!-- Fin Buscador -->
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Item</th>
                                        <th>Variable</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $key => $item)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $item->item }}</td>
                                        <td>{{ $item->variable->name }}</td>
                                        
                                        <td>
                                            <a class="btn btn-primary btn-circle" href="{{ route('formulario-items.edit',$item->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            {!! Form::open(['route' => ['formulario-items.destroy', $item->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar el item {{$item->item}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" style="text-align: center;">
                        {!! $items->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection