<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      <form class="navbar-form">

      </form>
      <ul class="navbar-nav">
        <li class="nav-item">
          @guest
          <a class="nav-link" href="#pablo">
            <i class="material-icons">dashboard</i>
            <p class="d-lg-none d-md-block">
              Stats
            </p>
          </a>
          @else
        </li>
        <li class="nav-item dropdown">

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @endguest
            <a class="dropdown-item" href="#">Notificaciones</a>
          </div>
        </li>
        <!-- Dropleft menu -->
    <div class="btn-group dropleft">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}</button>
        <div class="dropdown-menu">
            
            <a href="#" class="dropdown-item">Configurar</a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" class="dropdown-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
                Salir
            </a>
        </div>
    </div>

        
      </ul>
    </div>
  </div>
</nav>