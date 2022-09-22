@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h4 class="card-title ">Editar {{$servicio->name}}</h4>
                    </div>
                    <div class="card-body">
                        {!! Form::model($servicio, ['route'=>['globales.servicios.update', $servicio->id],
                        'method'=>'PUT']) !!}
                        @include('admin.globales.servicios.partials.form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection