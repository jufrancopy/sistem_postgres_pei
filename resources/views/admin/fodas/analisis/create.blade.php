@extends('layouts.master') 
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">{{$aspecto[0]->nombre}}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'foda-analisis.store','files'=>true]) !!}
                        @include('admin.fodas.analisis.partials.form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection