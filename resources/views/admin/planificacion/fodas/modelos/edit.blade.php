@extends('layouts.master')
@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title ">Editar Modelo</h4>
            <div class="pull-right">
              <a class="btn btn-warning" href="{{ route('peis.index', $objetivo->perfil_id) }}">
                Atras
              </a>
            </div>

          </div>

          <div class="card-body">
            {!! Form::model($modelo, ['route'=>['foda-modelos.update', $modelo->id],
            'method'=>'PUT']) !!}


            @include('admin.planificacion.fodas.modelos.partials.form')

            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection