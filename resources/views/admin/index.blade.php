<!-- Inicio Cabecera-->
          @include('layouts.includes.cabecera')
          <!-- Fin de Cabecera-->

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="azure" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <!-- Inicio Sidebar Izquierda -->
      @include('layouts.includes.sidebar_izq')
    </div>
    <div class="main-panel">
        
      <!-- Inicio Navbar Superior -->
      @include('layouts.includes.nav_bar') 
      <!-- Finaliza NavBar Superior -->
      
      <!-- Contenido Principal -->
      <div class="content">
        <div class="container-fluid">
          <!-- Inicio Cabecera con iconos -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">verified_user</i>
                  </div>
                  <p class="card-category">Roles</p>
                  <h3 class="card-title">
                    <small>{{$totalRoles}}</small>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats"><i class="material-icons">search</i>
                  <a href="{{route('roles.index')}}">Ver todo</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">visibility</i>
                  </div>
                  <p class="card-category">Permisos</p>
                  <h3 class="card-title">{{$totalPermisos}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="{{route('permisos.index')}}">Ver todo</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
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
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">description</i>
                  </div>
                  <p class="card-category">Otros</p>
                  <h3 class="card-title">11</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="#">Ver todo</a>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Fin de Cabecera con iconos -->
            @if(session('info'))
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        {{session('info')}}
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(count($errors))
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
          @yield('content')
      
   <!-- Inicio Pie -->
      @include('layouts.includes.pie') 
      <!-- Fin Pie -->
</body>
</html>