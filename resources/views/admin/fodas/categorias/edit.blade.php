@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Categoría</h4>
          </div>
          <div class="card-body">
            {!! Form::model($categoria, ['route'=>['foda-categorias.update', $categoria->id],
            'method'=>'PUT']) !!}
            @include('admin.fodas.categorias.partials.form')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection