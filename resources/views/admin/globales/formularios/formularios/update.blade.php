@extends('layouts.master')
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                    <h4 class="card-title ">Editar Formulario {{$formulario->formulario}}</h4>
                    </div>
                    
                    <div class="card-body">
                        {!! Form::model($formulario, ['route'=>['globales.form-response-ok', $formulario->id],
                        'method'=>'PUT']) !!}
                        @include('admin.globales.formularios.formularios.partials.update-form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection