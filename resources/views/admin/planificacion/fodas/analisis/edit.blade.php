@extends('layouts.master')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-info">
                            <h4 class="card-title ">Analisis de {{ $analisis->aspecto->nombre }} </h4>
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
                        <div class="card-body">
                            <h4>Referencia</h4>
                            {{ $analisis->aspecto->referencia }}
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
