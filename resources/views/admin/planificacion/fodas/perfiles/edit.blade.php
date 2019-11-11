@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Perfil</h4>
          </div>
          <div class="card-body">
          {!! Form::model($perfil, ['route'=>['foda-perfiles.update', $perfil->id],
			'method'=>'PUT'])	!!}
				@include('admin.planificacion.fodas.perfiles.partials.form')

				{!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection