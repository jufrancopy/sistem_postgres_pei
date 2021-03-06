@extends('layouts.master')
@section('content')
<!-- Contenido Principal -->
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header card-header-info">
        <h4 class="card-title ">Configuraciones Globales</h4>
        <div class="pull-right">
          <a class="btn btn-warning" href="{{ route('home') }}"> Atras</a>
        </div>
      </div>

      

      <div class="card-body">
        <!-- Inicio Cabecera con iconos -->
        <div class="row">
          
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">faces</i>
                </div>
                <p class="card-category">Peis</p>
                <h3 class="card-title">{{$totalPeiPerfil}}</h3>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <i class="material-icons">search</i>
                  <a href="{{route('users.index')}}">Ver todo</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                  <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">account_balance</i>
                    </div>
                    <p class="card-category">Fodas</p>
                    <h3 class="card-title">{{$totalFodaPerfil}}</h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      <i class="material-icons">search</i>
                      <a href="{{route('users.index')}}">Ver todo</a>
                    </div>
                  </div>
                </div>
              </div>
        </div>

        
        <!--Fin de Cabecera con iconos -->
        <p id="tree1"></p>
      </div>
    </div>

  </div>
</div>

@section('scripts')
<script>
        var data = [
        {
            name: 'Planificación Estratégica Institucional.',
            children: [
                {
                    name: 'Perfiles de Planificacion Estrategica',
                    children: [
                        { name: '<a href="{{route('peis-perfiles.create')}}">Crear Nuevo Perfil</a>'},
                        { name: '<a href="{{route('peis-perfiles.index')}}">Listar Perfiles</a>'},
                    ]
                },
                {
                    name: 'Analisis FODA',
                    children: [
                        { name: '<a href="{{route('foda-modelos.index')}}">Modelos</a>'},
                        { name: '<a href="{{route('foda-perfiles.index')}}">Perfiles</a>'},
                        { name: '<a href="{{route('foda-listado-perfiles')}}">Cruce de Ambientes</a>'},
                    ]
                },

                {
                    name: 'Mision - Vision',
                    children: [
                        { name: '<a href="{{route('foda-modelos.index')}}">Asociar Perfil</a>'},
                    ]
                }
            ]
        },    
        
        

        {
            name: 'Monitoreo y Evaluación',
            children: [
                { name: '<a href="{{route('monitoreo-tipo_evaluaciones.index')}}">Tipos de Evaluaciones</a>'},
                { name: '<a href="{{route('monitoreo-evaluaciones.index')}}">Evaluaciones</a>'},
                
                
            ]
        },

        
    ];

    $('#tree1').tree({
        data: data,
        autoEscape: false,
        saveState: true,
        closedIcon: $('<i class="fas fa-arrow-circle-right"></i>'),
        openedIcon: $('<i class="fas fa-arrow-circle-down"></i>'),
        autoOpen: true,
        openFolderDelay: 1000,
        dragAndDrop: true
    });
    </script>
@endsection

@endsection