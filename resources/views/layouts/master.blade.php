<!-- Inicio Cabecera-->
@include('layouts.includes.cabecera')
<!-- Fin de Cabecera-->

<body>
    @if(request()->has('iframe'))
    <style>
        .sidebar, .main-panel > nav.navbar, #impersonationBanner, footer.footer { display: none !important; }
        .main-panel { float: none !important; width: 100% !important; }
        .content { padding-top: 10px !important; margin-top: 0 !important; }
        body, html { background: #f8fafc !important; }
    </style>
    @endif
    @if(session()->has('impersonator_id'))
        <div id="impersonationBanner" style="background: linear-gradient(135deg, #d97706, #b45309); color: #ffffff; padding: 9px 24px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 999999; box-shadow: 0 4px 14px rgba(0,0,0,0.25); font-size: 0.88rem;">
            <div class="d-flex align-items-center" style="gap: 12px;">
                <span class="badge badge-light text-dark font-weight-bold" style="font-size: 0.78rem; padding: 4px 10px; border-radius: 6px;">
                    <i class="fa fa-eye mr-1 text-warning"></i> MODO VISTA PREVIA
                </span>
                <span>
                    Estás navegando como: <strong style="font-size:0.95rem;">{{ auth()->user()->name }}</strong>
                    <span class="badge badge-warning text-dark ml-2" style="font-size: 0.75rem; padding: 3px 8px; border-radius: 4px;">
                        Rol: {{ auth()->user()->roles->pluck('name')->implode(', ') ?: 'Sin rol' }}
                    </span>
                </span>
            </div>
            <a href="{{ route('impersonate.leave') }}" class="btn btn-sm btn-light font-weight-bold text-dark shadow-sm" style="border-radius: 20px; padding: 4px 16px;">
                <i class="fa fa-sign-out-alt mr-1 text-danger"></i> Volver a mi Administrador
            </a>
        </div>
    @endif
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
                    @if (session('info'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    {{ session('info') }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (count($errors))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <script>
                                    $('.alert').slideDown();
                                    setTimeout(function() {
                                        $('.alert').slideUp();
                                    }, 10000)
                                </script>
                            </div>
                        </div>
                    @endif

                    <main class="py-4">
                        @yield('content')
                    </main>

                    <!-- Inicio Pie -->
                    @include('layouts.includes.pie')
                    <!-- Fin Pie -->

                    @include('layouts.includes.reflexion_modal')
                    @include('layouts.includes.ticket_modal')
</body>

</html>
