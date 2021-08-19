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
                        <h4 class="card-title ">Variables</h4>

                        <a class="btn btn-success" href="{{ route('formulario-variables.create') }}">Nueva Variable</a>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{ route('globales-dashboard') }}"> Atras</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <!-- AquiBuscador -->
                                <div class="float-right">
                                    {!! Form::open(['route' => 'formulario-variables.index','method' => 'GET', 'class'=>'navbar-form navbar-left pull-right','role'=>'search']) !!}
                                    <div class="form-group">
                                        {!! Form::text('variable',null, ['class'=>'form-control','placeholder'=>'Buscar Variable']) !!}
                                    </div>

                                    <button type="submit" class="btn btn-default pull-right">Buscar</button>

                                </div>

                                {!!Form::close()!!}
                                <!-- Fin Buscador -->
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Variable</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($variables as $key => $variable)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $variable->variable }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-circle" href="{{ route('formulario-variables.edit',$variable->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            {!! Form::open(['route' => ['formulario-variables.destroy', $variable->id], 'method' => 'DELETE', 'style'=>'display:inline']) !!}
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Estas seguro de eliminar la variable {{$variable->variable}}. Si lo eliminas también eliminarás los datos asociados a el.')">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>

                                            <a href="{{route('formulario-variables-items',['idVariable'=>$variable->id])}}">
                                                    <span class="btn btn-success btn-circle">{{App\Admin\Globales\Formulario\Item::where('variable_id', $variable->id)->count()}}</span>
                                            </a>

                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" style="text-align: center;">
                        {!! $variables->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection