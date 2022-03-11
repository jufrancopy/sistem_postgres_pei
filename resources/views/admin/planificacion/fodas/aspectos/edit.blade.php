@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar {{$aspecto->nombre}} </h4>
          </div>
          <div class="card-body">
            {!! Form::model($aspecto, ['route'=>['foda-aspectos.update', $aspecto->id],
            'method'=>'PUT']) !!}


            @include('admin.planificacion.fodas.aspectos.partials.form')

            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection