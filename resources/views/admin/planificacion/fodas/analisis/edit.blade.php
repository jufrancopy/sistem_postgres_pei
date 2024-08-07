@extends('layouts.master')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title ">Analisis de {{ $analisis->aspecto->name }} </h4>
                        </div>
                        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('foda-perfiles.index') }}">Planificación-Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('foda-perfiles.index') }}">Perfiles Foda</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Ambientes</li>
                            </ol>
                        </nav>
                        <div class="card-header">
                            <h4>Referencia</h4>
                            <h6 class="bg-warning">{!! $analisis->aspecto->description !!}</h6>
                        </div>
                        <div class="card-body">


                            <hr>
                            {!! Form::model($analisis, ['route' => ['foda-analisis.update', $analisis->id], 'method' => 'PUT']) !!}
                            @include('admin.planificacion.fodas.analisis.partials.form')

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
