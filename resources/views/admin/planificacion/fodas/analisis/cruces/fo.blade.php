@extends('layouts.master')
@section('title', 'Crear Estrategias FO')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title ">Crear Estrategias FO</b>
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

                        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                @if (isset($profile))
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('foda-matriz-groups-crossing', $profile->group_id) }}">Matriz
                                            Foda - {{ $profile->name }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Estrategias
                                        FO: {{ $profile->name }}</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">Estrategias FO</li>
                                @endif
                            </ol>
                        </nav>

                        <div class="card-body">
                            {!! Form::open(['route' => 'foda-cruce-ambientes.store', 'files' => true]) !!}
                            <div class="content">

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <!-- Campos ocultos que se insertan en la tabla foda_analisis. -->
                                        {{ Form::hidden('user_id', auth()->user()->id) }}
                                        {{ Form::hidden('perfil_id', $idPerfil) }}
                                        {{ Form::hidden('tipo', 'FO') }}
                                        <!-- Array de Fortalezas -->
                                        <strong>Fortalezas:</strong>
                                        <br />
                                        @foreach ($fortalezas as $value)
                                            <label>{{ Form::checkbox('fortaleza_id[]', $value->aspecto->id, false, ['class' => 'name']) }}
                                                F{{ $value->aspecto->id }} - {{ $value->aspecto->name }}</label>
                                            <br />
                                        @endforeach

                                        <!-- Array de Oportunidades -->
                                        <strong>Oportunidades:</strong>
                                        <br />
                                        @foreach ($oportunidades as $value)
                                            <label>{{ Form::checkbox('oportunidad_id[]', $value->aspecto->id, false, ['class' => 'name']) }}
                                                O{{ $value->aspecto->id }} - {{ $value->aspecto->name }}</label>
                                            <br />
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('estrategia', 'Estrategia:') }}
                                        {{ Form::text('estrategia', null, ['class' => 'form-control', 'id' => 'nombre']) }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <button type="submit" class="btn btn-success">Crear Estrategia FO</button>
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
