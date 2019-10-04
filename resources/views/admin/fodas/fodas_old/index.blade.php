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
                        <h4 class="card-title ">Matriz FODA</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">call_received

                                            </i>
                                        </div>
                                        <p class="card-category">Ambiente Interno</p>
                                        <h3 class="card-title">
                                            <small>11</small>
                                        </h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="material-icons">visibility</i> <a href="{{route('foda-amb-interno')}}">Analizar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">call_made
                                            </i>
                                        </div>
                                        <p class="card-category">Ambiente Externo</p>
                                        <h3 class="card-title">
                                            <small>11</small>
                                        </h3>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="material-icons">visibility</i> <a href="#">Analizar</a>
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
</div>

@endsection