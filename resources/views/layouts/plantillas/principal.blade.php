<!-- Inicio Cabecera-->
          @include('layouts.includes.cabecera')
          <!-- Fin de Cabecera-->

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <!-- Inicio Sidebar Izquierda -->
      @include('layouts.usuarios.includes.sidebar_izq')
    </div>
    <div class="main-panel">
        
      <!-- Inicio Navbar Superior -->
      @include('layouts.usuarios.includes.nav_bar') 
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
                    <i class="material-icons">local_library</i>
                  </div>
                  <p class="card-category">Alumnos</p>
                  <h3 class="card-title">
                    <small>{{$cantidadAlumnos}}</small>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats"><i class="material-icons">search</i>
                  <a href="{{Route('us_alumnos.index')}}">Ver todo</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">school</i>
                  </div>
                  <p class="card-category">Egresados</p>
                  <h3 class="card-title">{{$cantidadEgresados}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="{{Route('us_egresados.index')}}">Ver todo</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">person</i>
                  </div>
                  <p class="card-category">Profesores</p>
                  <h3 class="card-title">{{$cantidadProfesores}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="{{Route('us_profesores.index')}}">Ver todo</a>
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
                  <p class="card-category">Matriculados</p>
                  <h3 class="card-title">{{$cantidadMatriculados}}</h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">search</i>
                    <a href="{{Route('us_matriculaciones.index')}}">Ver todo</a>
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