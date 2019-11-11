@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
          

            
            <h4 class="card-title ">{{ isset($aspecto[0]->nombre) ? $aspecto[0]->nombre : '' }}</h4>
          </div>
          <div class="card-body">
            {!! Form::model($analisis, ['route'=>['foda-analisis.update', isset($analisis[0]->aspecto_id) ? $analisis[0]->aspecto_id : '' ],
            'method'=>'PUT']) !!}
            @include('admin.fodas.analisis.partials.form')
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

