@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'home', 'title' => __('Material Dashboard')])

@section('content')
<div class="container" style="height: auto;">
  <div class="row justify-content-center">
      <div class="col-lg-7 col-md-8">
          <h1 class="text-white text-center">{{ __('Bienvenidos a la Aplicación del Dpto. de Planificación y evaluación') }}</h1>

          <button class="btn btn-info">
            <a href="{{route('login')}}" class=" text-white">Ingresar</a>
          </button>
      </div>
  </div>

  
</div>
@endsection
