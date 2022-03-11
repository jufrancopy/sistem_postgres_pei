@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Clasficiador</h4>
          </div>
          <div class="card-body">
            {!! Form::model($clasificador, ['route'=>['formulario-clasificadores.update', $clasificador->id],
            'method'=>'PUT']) !!}
            @include('admin.globales.formularios.clasificadores.partials.form')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection