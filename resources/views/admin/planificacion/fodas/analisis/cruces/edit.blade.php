@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Estrategia {{$cruce->tipo}}</h4>
          </div>
          <div class="card-body">
            {!! Form::model($cruce, ['route'=>['foda-cruce-ambientes.update', $cruce->id],
            'method'=>'PUT']) !!}


            @include('admin.planificacion.fodas.analisis.cruces.partials.form')

            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection