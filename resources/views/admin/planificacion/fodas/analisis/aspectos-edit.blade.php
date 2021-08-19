@extends('layouts.master') @section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Aspectos de la Categoría {{$categoria->nombre}}</b>

                        <div class="pull-right">
                            <a class="btn btn-warning" href="{{route('foda-analisis-ambiente-interno', $idPerfil)}}"> Atras</a>
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
                    {!! Form::model($analisis, ['route'=>['foda-analisis.update', $v->id],'method'=>'PUT'])	!!}
                        
                        <div class="content">

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <!-- Campos ocultos que se insertan en la tabla foda_analisis. -->
                                    {{ Form::hidden('user_id', auth()->user()->id) }}
                                    {{ Form::hidden('perfil_id', $idPerfil) }}
                                    {{ Form::hidden('tipo', 'Pendiente') }}
                                    {{ Form::hidden('ocurrencia', 0) }}
                                    {{ Form::hidden('impacto', 0) }}
                                    {{ Form::hidden('categoria_id', $idCategoria) }}

                                    <!-- Esta parte del Codigo Imprime los aspectos relacionados a la categoria seleccionada -->
                                    @if (count($aspectos) > 1)
                                    <strong>Listado de Aspectos:</strong>
                                    <br /> @foreach($aspectos as $value)
                                    <label>{{ Form::checkbox('aspecto_id[]', $value->id, in_array($value->id, $aspectosChecked) ? true : false, array('class' => 'name')) }}
                                        {{ $value->nombre }}</label>
                                    <br /> @endforeach
                                    </div>
                                </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">Analizar Aspectos</button>
                                    </div>
                                </div>
                        {!! Form::close() !!}
                                    
                                    @else
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-success"><a href="{{route('foda-analisis-ambiente-interno', $idPerfil)}}">Sin Aspectos</a></button>
                                    </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection