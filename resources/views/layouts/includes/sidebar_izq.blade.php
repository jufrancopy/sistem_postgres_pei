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

            {{-- ── SIESS ── --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#siessMenu" aria-expanded="false">
                    <i class="material-icons">analytics</i>
                    <p>SIESS
                        @php
                            $pendientesSiess = \App\Models\Estadistica\SiessExtracto::pendientes()->count();
                        @endphp
                        @if($pendientesSiess > 0)
                            <span class="badge badge-warning ml-1" style="font-size:.65rem">{{ $pendientesSiess }}</span>
                        @endif
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="siessMenu">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('siess.dashboard') }}">
                                <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('siess.extractos.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-database" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Extractos
                                    @if($pendientesSiess > 0)
                                        <span class="badge badge-warning" style="font-size:.6rem">{{ $pendientesSiess }}</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#siessModulos" aria-expanded="false">
                                <span class="sidebar-mini"><i class="fa fa-layer-group" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Módulos <b class="caret"></b></span>
                            </a>
                            <div class="collapse" id="siessModulos">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.aop') }}">
                                            <span class="sidebar-mini">AOP</span>
                                            <span class="sidebar-normal">Aportes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.ju') }}">
                                            <span class="sidebar-mini">JU</span>
                                            <span class="sidebar-normal">Jubilaciones</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.rl') }}">
                                            <span class="sidebar-mini">RL</span>
                                            <span class="sidebar-normal">Subsidios</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.di') }}">
                                            <span class="sidebar-mini">DI</span>
                                            <span class="sidebar-normal">Inversiones</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.dt') }}">
                                            <span class="sidebar-mini">DT</span>
                                            <span class="sidebar-normal">Tesorería</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.rh') }}">
                                            <span class="sidebar-mini">RH</span>
                                            <span class="sidebar-normal">Rec. Humanos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('siess.modulos.cau') }}">
                                            <span class="sidebar-mini">CAU</span>
                                            <span class="sidebar-normal">Atención</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('siess.reportes.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-file-pdf" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Reportes</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('proyectos-dashboard') }}">
                    <i class="material-icons">next_week</i>
                    <p>Proyectos</p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="{{ route('globales.patrimony-profiles.index') }}">
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
        {{-- ── Sidebar para Participantes / Validadores SIESS ── --}}
        @php
            $orgParticipante = \App\Admin\Globales\Organigrama::where('user_id', Auth::id())->first();
            $pendientesParticipante = $orgParticipante
                ? \App\Models\Estadistica\SiessExtracto::pendientes()->where('direccion_id', $orgParticipante->id)->count()
                : 0;
        @endphp

        <li class="nav-item">
            <a class="nav-link" href="{{ route('siess.home') }}">
                <i class="material-icons">home</i>
                <p>Inicio</p>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('siess.home') }}">
                <i class="material-icons">notifications</i>
                <p>Mis Notificaciones
                    @if($pendientesParticipante > 0)
                        <span class="badge badge-warning ml-1" style="font-size:.65rem">{{ $pendientesParticipante }}</span>
                    @endif
                </p>
            </a>
        </li>

        @if($orgParticipante)
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#validacionMenu" aria-expanded="{{ $pendientesParticipante > 0 ? 'true' : 'false' }}">
                <i class="material-icons">fact_check</i>
                <p>Validación SIESS
                    @if($pendientesParticipante > 0)
                        <span class="badge badge-danger ml-1" style="font-size:.65rem">{{ $pendientesParticipante }}</span>
                    @endif
                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse {{ $pendientesParticipante > 0 ? 'show' : '' }}" id="validacionMenu">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siess.home') }}">
                            <span class="sidebar-mini"><i class="fa fa-clock" style="font-size:.8rem"></i></span>
                            <span class="sidebar-normal">Pendientes
                                @if($pendientesParticipante > 0)
                                    <span class="badge badge-danger" style="font-size:.6rem">{{ $pendientesParticipante }}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('siess.extractos.index') }}">
                            <span class="sidebar-mini"><i class="fa fa-list" style="font-size:.8rem"></i></span>
                            <span class="sidebar-normal">Todos los extractos</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.edit') }}">
                <i class="material-icons">person</i>
                <p>Mi Perfil</p>
            </a>
        </li>
    @endhasanyrole
</div>
