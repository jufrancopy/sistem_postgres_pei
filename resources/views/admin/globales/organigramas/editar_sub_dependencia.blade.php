@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Sub-Dependencia</h4>
            <div class="pull-right">
              <a class="btn btn-warning" href="{{ route('globales.organigrama-gestionar', $idDependencia) }}"> Atras</a>
          </div>
          </div>
          <div class="card-body">
            {!! Form::model($dependencia, ['route'=>['globales.organigramas.update', $dependencia->id],
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