@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Dependencia</h4>
          </div>
          <div class="card-body">
            {!! Form::model($dependencia, ['route'=>['organigramas.update', $dependencia->id],
            'method'=>'PUT']) !!}
            @include('admin.globales.organigramas.partials.form-crear-sub-dependencia')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection