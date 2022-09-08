<!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
<div class="logo">
    <a href="{{route('home')}}" class="simple-text logo-normal">
        SIMOG
    </a>
</div>
<div class="sidebar-wrapper">
    
    <ul class="nav">
        {{-- Si tenemos una coleccion de roles pasarmos ('RoleA|RoleB') --}}
        @hasanyrole('Administrator')
        <li class="nav-item active  ">
            <a class="nav-link" href="{{url('home')}}">
                <i class="material-icons">dashboard</i>
                <p>Administrador</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route('globales-dashboard')}}">
                <i class="material-icons">settings_applications</i>
                <p>Globales</p>
            </a>
        </li>
        
        <li class="nav-item ">
            <a class="nav-link" href="{{route('planificacion-dashboard')}}">
                <i class="material-icons">assignment</i>
                <p>Planificacion</p>
            </a>
        </li>
        
        <li class="nav-item ">
            <a class="nav-link" href="{{route('estadisticas-dashboard')}}">
                <i class="material-icons">bar_chart</i>
                <p>Estadisticas</p>
            </a>
        </li>

        <li class="nav-item ">
            <a class="nav-link" href="{{route('proyectos-dashboard')}}">
                <i class="material-icons">next_week</i>
                <p>Proyectos</p>
            </a>
        </li>
    </ul>
    @else
    
    <li class="nav-item ">
        <a class="nav-link" href="{{route('planificacion-dashboard')}}">
            <i class="material-icons">assignment</i>
            <p>Planificacion</p>
        </a>
    </li>
    @endhasanyrole
</div>