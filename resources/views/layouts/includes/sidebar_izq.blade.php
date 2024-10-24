<!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
<div class="logo">
    <a href="{{ route('planificacion-dashboard') }}" class="simple-text logo-normal">
        SIPLAN
    </a>
</div>
<div class="sidebar-wrapper">

    <ul class="nav">
        {{-- Si tenemos una coleccion de roles pasarmos ('RoleA|RoleB') --}}
        @hasanyrole('Administrador')
            <li class="nav-item active  ">
                <a class="nav-link" href="{{ url('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Administrador</p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('globales.dashboard') }}">
                    <i class="material-icons">settings_applications</i>
                    <p>Globales</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('planificacion-dashboard') }}">
                    <i class="material-icons">assignment</i>
                    <p>Planificacion</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('estadisticas-dashboard') }}">
                    <i class="material-icons">bar_chart</i>
                    <p>Estadisticas</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('proyectos-dashboard') }}">
                    <i class="material-icons">next_week</i>
                    <p>Proyectos</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('globales.patrimonies.index') }}">
                    <i class="material-icons">account_balance</i>
                    <p>Patrimonios</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('globales.activities.index') }}">
                    <i class="material-icons">rocket_launch</i>
                    <p>Acitividades</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('surveys.index') }}">
                    <i class="material-icons">poll </i>
                    <p>Encuesta</p>
                </a>
            </li>
        </ul>
    @else
        <li class="nav-item ">
            <a class="nav-link" href="{{ route('tasks.index') }}">
                <i class="material-icons">assignment</i>
                <p>Mis Tareas</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{ route('tasks-list-tree') }}">
                <i class="material-icons">assignment</i>
                <p>Arbol de Tareas</p>
            </a>
        </li>
        {{-- <li class="nav-item ">
            <a class="nav-link" href="{{ route('foda-list-groups') }}">
                <i class="material-icons">assignment</i>
                <p>Cruce de Ambientes</p>
            </a>
        </li> --}}
    @endhasanyrole
</div>
