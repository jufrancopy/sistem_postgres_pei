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
              <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                  <i class="material-icons">faces</i>
                </div>
                <p class="card-category">Usuarios</p>
                <h3 class="card-title">{{$totalUsuarios}}</h3>
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
                  <div class="card-header card-header-primary card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">account_balance</i>
                    </div>
                    <p class="card-category">Orgs</p>
                    <h3 class="card-title">{{$totalDependencias}}</h3>
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
            name: 'Roles y Permisos.',
            children: [
                { name: '<a href="{{route('users.index')}}">Usuarios</a>'},
                { name: '<a href="{{route('roles.index')}}">Roles</a>'},
                { name: '<a href="{{route('permisos.index')}}">Permisos</a>'},
            ]
        },    

        {
            name: 'Institucionales',
            children: [
                { name: '<a href="{{route('organigramas-listado')}}">Dependencias</a>'},
                { name: '<a href="{{route('estructuras-control.index')}}">Estructuras de Control</a>'}
            ]
        },

        {
            name: 'Formularios',
            children: [
                { name: '<a href="{{route('formulario-formularios.index')}}">Formularios</a>'},
                { name: '<a href="{{route('formulario-variables.index')}}">Variables</a>'},
                { name: '<a href="{{route('formulario-clasificadores-listado')}}">Clasificadores</a>'}
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