@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Crear Estrategias DA</b>

                            <div class="pull-right">
                                <a class="btn btn-warning" href="#"> Atras</a>
                            </div>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Hubo algunos problemas con su entrada.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="card-body">
                        {!! Form::open(['route' => 'foda-cruce-ambientes.store','files'=>true]) !!}


                        <div class="content">

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <!-- Campos ocultos que se insertan en la tabla foda_analisis. -->
                                    {{ Form::hidden('user_id', auth()->user()->id) }}
                                    {{ Form::hidden('perfil_id', $idPerfil) }}
                                    {{ Form::hidden('tipo', 'DA') }}
                                    
                                    <!-- Array de Debilidades -->
                                    <strong>Debilidades:</strong>
                                    <br /> @foreach($debilidades as $value)
                                    <label>{{ Form::checkbox('debilidad_id[]', $value->id, false, array('class' => 'name')) }}
                                        D{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
                                    <br /> @endforeach

                                    <!-- Array de Amenazas -->
                                    <strong>Amenazas:</strong>
                                    <br /> @foreach($amenazas as $value)
                                    <label>{{ Form::checkbox('amenaza_id[]', $value->id, false, array('class' => 'name')) }}
                                        A{{ $value->id  }} - {{ $value->aspecto->nombre }}</label>
                                    <br /> @endforeach
                                </div>
                                <div class="form-group">
                                    {{ Form::label('estrategia', 'Estrategia:')	}}
                                    {{ Form::text('estrategia', null,['class'=>'form-control','id'=>'nombre'])	}}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-success">Crear Estrategia DA</button>
                            </div>
                        </div>
                        {!! Form::close() !!}


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection