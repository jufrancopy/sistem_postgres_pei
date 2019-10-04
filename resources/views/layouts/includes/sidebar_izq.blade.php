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
        <li class="nav-item active  ">
            <a class="nav-link" href="{{url('home')}}">
                <i class="material-icons">dashboard</i>
                <p>Administrador</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route('organigramas.index')}}">
                <i class="material-icons">account_balance</i>
                <p>Organigrama</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route('fodas-dashboard')}}">
                <i class="material-icons">visibility</i>
                <p>FODA</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route('accesos')}}">
                <i class="material-icons">person</i>
                <p>Roles y Permisos</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{route('users.index')}}">
                <i class="material-icons">content_paste</i>
                <p>Usuarios</p>
            </a>
        </li>
    </ul>
</div>