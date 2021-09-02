<!-- Inicio Cabecera-->
@include('layouts.includes.cabecera')
<!-- Fin de Cabecera-->

<body>
  <div class="wrapper ">
    <div class="sidebar" data-color="azure" data-background-color="white" data-image="#">
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
          @if(session('info'))
          <div class="row">
              <div class="col-md-12">
                <div class="alert alert-success">
                  {{session('info')}}
                </div>
              </div>
            </div>
          @endif
          @if(count($errors))
          div class="row">
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
          @endif
          <main class="py-4">
            
              @yield('content')
            
          </main> <!-- Inicio Pie -->
          @include('layouts.includes.pie')
          <!-- Fin Pie -->
</body>

</html>