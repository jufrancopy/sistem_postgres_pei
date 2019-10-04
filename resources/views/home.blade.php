@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">local_library</i>
                                    </div>
                                    <p class="card-category">Análisis Foda</p>
                                    <h3 class="card-title">
                                        <small><b>{{$totalPerfiles}}</b> Perfiles</small>
                                    </h3>
                                </div>
                                <div class="card-footer">
                                    <div class="stats"><i class="material-icons">search</i>
                                        <a href="{{route('foda-listado-perfiles')}}">Ver Perfiles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection