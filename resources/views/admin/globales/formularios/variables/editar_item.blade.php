@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar {{$variable->name}}</h4>
            <div class="pull-right">
              <a class="btn btn-warning" href="{{ route('globales.gestionar-variable', $rootId) }}"> Atras</a>
          </div>
          </div>
          <div class="card-body">
            {!! Form::model($variable, ['route'=>['globales.variables.update', $variable->id],
            'method'=>'PUT']) !!}
            @include('admin.globales.formularios.variables.partials.form-crear-sub-item')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection