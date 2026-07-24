<!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
@php
    $url = request()->url();
    $path = request()->path();

    // Detectar en qué sección estamos
    $enPlanificacion = str_contains($path, 'pei-') || str_contains($path, 'foda') || str_contains($path, 'planificacion') || str_contains($path, 'tasks') || str_contains($path, 'pgn') || str_contains($path, 'pei/marcos');
    $enProyectos     = str_contains($path, 'proyectos') || str_contains($path, 'epc');
    $enSiess         = str_contains($path, 'siess') || str_contains($path, 'eph') || str_contains($path, 'dgeec') || str_contains($path, 'contexto');
    $enCronogramas   = str_contains($path, 'cronogramas');
    $enModulosSiess  = str_contains($path, 'siess/modulos');
    $enFoda          = str_contains($path, 'foda');

    // Helper para marcar link activo
    $isActive = fn($routePattern) => request()->is($routePattern) ? 'active' : '';
@endphp
<div class="logo">
    <a href="{{ route('planificacion-dashboard') }}" class="simple-text logo-normal">
        SIPLAN
    </a>
</div>
<div class="sidebar-wrapper">

    <ul class="nav">
        {{-- Menu para Administrador (todo) --}}
        @role('Administrador')
            <li class="nav-item active  ">
                <a class="nav-link" href="{{ url('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Administrador</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}" target="_blank">
                    <i class="material-icons">public</i>
                    <p>Ver sitio público</p>
                </a>
            </li>
            <li class="nav-item {{ request()->is('perfil*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.profile') }}">
                    <i class="material-icons">account_circle</i>
                    <p>Mi Perfil y Insignias</p>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('globales.dashboard') }}">
                    <i class="material-icons">settings_applications</i>
                    <p>Globales</p>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#planificacionMenu" aria-expanded="{{ $enPlanificacion ? 'true' : 'false' }}">
                    <i class="material-icons">assignment</i>
                    <p>Planificación <b class="caret"></b></p>
                </a>
                <div class="collapse {{ $enPlanificacion ? 'show' : '' }}" id="planificacionMenu">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('planificacion-dashboard') }}">
                            <a class="nav-link" href="{{ route('planificacion-dashboard') }}">
                                <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('pei-profiles*') }}">
                            <a class="nav-link" href="{{ route('pei-profiles.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-file-alt" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">PEI</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $enFoda ? 'active' : '' }}" data-toggle="collapse" href="#fodaMenu" aria-expanded="{{ $enFoda ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-random" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">FODA <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enFoda ? 'show' : '' }}" id="fodaMenu">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $isActive('foda-list-groups') }}">
                                        <a class="nav-link" href="{{ route('foda-list-groups') }}">
                                            <span class="sidebar-mini"><i class="fa fa-search" style="font-size:.75rem"></i></span>
                                            <span class="sidebar-normal">Análisis</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('foda-perfiles*') }}">
                                        <a class="nav-link" href="{{ route('foda-perfiles.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-layer-group" style="font-size:.75rem"></i></span>
                                            <span class="sidebar-normal">Perfiles</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('foda-models*') }}">
                                        <a class="nav-link" href="{{ route('foda-models.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-cubes" style="font-size:.75rem"></i></span>
                                            <span class="sidebar-normal">Modelos</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ $isActive('plan-maestro*') }}">
                            <a class="nav-link" href="{{ route('plan-maestro.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-shield-halved" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Plan Maestro</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('tasks*') }}">
                            <a class="nav-link" href="{{ route('tasks.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-tasks" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Tareas</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('pgn*') }}">
                            <a class="nav-link" href="{{ route('pgn.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-coins" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">PGN</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('pei/marcos*') }}">
                            <a class="nav-link" href="{{ route('pei.marcos.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-link" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Marcos Referenciales</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#proyectosMenu" aria-expanded="{{ $enProyectos ? 'true' : 'false' }}">
                    <i class="material-icons">account_tree</i>
                    <p>Proyectos
                        @php $sinPei = \App\Models\Proyectos\ProyectoInstitucional::whereNull('pei_profile_id')->activos()->count(); @endphp
                        @if($sinPei > 0)
                            <span class="badge badge-warning ml-1" style="font-size:.65rem">{{ $sinPei }}</span>
                        @endif
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $enProyectos ? 'show' : '' }}" id="proyectosMenu">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('proyectos-dashboard') }}">
                            <a class="nav-link" href="{{ route('proyectos-dashboard') }}">
                                <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Dashboard</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- ── Estadísticas / SIESS ── --}}
            @php
                $pendientesSiess = \App\Models\Estadistica\SiessExtracto::pendientes()->count();
            @endphp
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#siessMenu" aria-expanded="{{ $enSiess ? 'true' : 'false' }}">
                    <i class="material-icons">bar_chart</i>
                    <p>Estadísticas
                        @if($pendientesSiess > 0)
                            <span class="badge badge-warning ml-1" style="font-size:.65rem">{{ $pendientesSiess }}</span>
                        @endif
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $enSiess ? 'show' : '' }}" id="siessMenu">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('siess') }}">
                            <a class="nav-link" href="{{ route('siess.dashboard') }}">
                                <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('siess/extractos*') }}">
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
                            <a class="nav-link {{ $enModulosSiess ? 'active' : '' }}" data-toggle="collapse" href="#siessModulos" aria-expanded="{{ $enModulosSiess ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-layer-group" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Módulos <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enModulosSiess ? 'show' : '' }}" id="siessModulos">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $isActive('siess/modulos/aop') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.aop') }}">
                                            <span class="sidebar-mini">AOP</span>
                                            <span class="sidebar-normal">Aportes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/modulos/ju') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.ju') }}">
                                            <span class="sidebar-mini">JU</span>
                                            <span class="sidebar-normal">Jubilaciones</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/modulos/rl') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.rl') }}">
                                            <span class="sidebar-mini">RL</span>
                                            <span class="sidebar-normal">Subsidios</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/modulos/di') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.di') }}">
                                            <span class="sidebar-mini">DI</span>
                                            <span class="sidebar-normal">Inversiones</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/modulos/dt') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.dt') }}">
                                            <span class="sidebar-mini">DT</span>
                                            <span class="sidebar-normal">Tesorería</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/modulos/rh') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.rh') }}">
                                            <span class="sidebar-mini">RH</span>
                                            <span class="sidebar-normal">Rec. Humanos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/modulos/cau') }}">
                                        <a class="nav-link" href="{{ route('siess.modulos.cau') }}">
                                            <span class="sidebar-mini">CAU</span>
                                            <span class="sidebar-normal">Atención</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ $isActive('siess/reportes*') }}">
                            <a class="nav-link" href="{{ route('siess.reportes.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-file-pdf" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Reportes</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('siess/eph*') }}">
                            <a class="nav-link" href="{{ route('siess.eph.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-globe" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">EPH / INE</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>



            {{-- ── RIISS (dentro del menú Administrador) ── --}}
            @php $enRiiss = str_contains($path, 'riiss'); @endphp
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#riissMenu" aria-expanded="{{ $enRiiss ? 'true' : 'false' }}">
                    <i class="material-icons">local_hospital</i>
                    <p>RIISS <b class="caret"></b></p>
                </a>
                <div class="collapse {{ $enRiiss ? 'show' : '' }}" id="riissMenu">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('riiss/establecimientos') }}">
                            <a class="nav-link" href="{{ route('riiss.establecimientos.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-hospital" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Establecimientos</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/evaluaciones') }}">
                            <a class="nav-link" href="{{ route('riiss.evaluaciones.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-clipboard-check" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Evaluaciones</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/asignaciones') }}">
                            <a class="nav-link" href="{{ route('riiss.asignaciones.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-user-check" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Asignaciones</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/dashboard') }}">
                            <a class="nav-link" href="{{ route('riiss.dashboard') }}">
                                <span class="sidebar-mini"><i class="fa fa-chart-line" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Monitoreo</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/formularios*') }}">
                            <a class="nav-link" href="{{ route('riiss.formularios.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-wpforms" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Formularios</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/complejidad*') }}">
                            <a class="nav-link" href="{{ route('riiss.complejidad.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-layer-group" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Complejidad</span>
                            </a>
                        </li>
                    </ul>
                </div>
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
        @endrole

        {{-- Mis Actividades: un solo item para todos los roles de actividades --}}
        @hasanyrole('Gestor de Actividades|Colaborador de Actividades|Analista de Planificación')
            <li class="nav-item {{ request()->is('mis-actividades') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('globales.activities.mis-actividades') }}">
                    <i class="material-icons">task_alt</i>
                    <p>Mis Actividades</p>
                </a>
            </li>
        @endhasanyrole

        {{-- Sidebar exclusivo para Analista PEI --}}
        @role('Analista PEI')
            @php $enPgnAnalista = str_contains($path, 'pgn') || str_contains($path, 'pei-'); @endphp
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('planificacion-dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Inicio PEI</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#peiAnalistaMenu" aria-expanded="{{ $enPgnAnalista ? 'true' : 'false' }}">
                    <i class="material-icons">assignment</i>
                    <p>Planificación <b class="caret"></b></p>
                </a>
                <div class="collapse {{ $enPgnAnalista ? 'show' : '' }}" id="peiAnalistaMenu">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('pei-profiles*') }}">
                            <a class="nav-link" href="{{ route('pei-profiles.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-file-alt" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">PEI</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('pgn*') }}">
                            <a class="nav-link" href="{{ route('pgn.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-coins" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">PGN</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endrole

        {{-- Sidebar exclusivo para Analista de Monitoreo PEI --}}
        @role('Analista de Monitoreo PEI')
        @php
            $userId = Auth::id();
            $orgId  = \App\Admin\Globales\Organigrama::where('user_id', $userId)->value('id');
            $peisMisAcciones = collect();
            if ($orgId) {
                // IDs de perfiles donde este usuario es responsable
                $profileIds = \DB::table('planificacion.peis_profiles_has_responsibles')
                    ->where('responsible_id', $orgId)
                    ->pluck('profile_id');

                // Buscar los master raíz que contienen esos perfiles
                $masterIds = \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $profileIds)
                    ->get()
                    ->map(function($p) {
                        // Subir al ancestro master
                        return \App\Admin\Planificacion\Pei\PeiProfile::where('_lft', '<=', $p->_lft)
                            ->where('_rgt', '>=', $p->_rgt)
                            ->where('level', 'master')
                            ->value('id');
                    })
                    ->filter()
                    ->unique()
                    ->values();

                $peisMisAcciones = \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $masterIds)
                    ->orderBy('year_start', 'desc')
                    ->get(['id', 'name', 'year_start', 'year_end']);
            }
            $enMonitoreo = str_contains($path, 'mis-acciones');
        @endphp
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('pei.monitoreo.dashboard') }}">
                    <i class="material-icons">track_changes</i>
                    <p>Mis Acciones PEI</p>
                </a>
            </li>
            @foreach($peisMisAcciones as $peiItem)
            <li class="nav-item {{ $isActive('pei-profiles/'.$peiItem->id.'/mis-acciones') }}">
                <a class="nav-link" href="{{ route('pei.reportes.mis-acciones', $peiItem->id) }}">
                    <span class="sidebar-mini">
                        <span style="width:22px;height:22px;border-radius:5px;background:rgba(255,193,7,.25);display:inline-flex;align-items:center;justify-content:center">
                            <i class="fa fa-file-alt" style="font-size:.7rem;color:#ffc107"></i>
                        </span>
                    </span>
                    <span class="sidebar-normal" style="line-height:1.3">
                        <span style="display:block;font-size:.78rem;color:#ffd54f;font-weight:600">
                            {{ \Illuminate\Support\Str::limit(strip_tags($peiItem->name), 28) }}
                        </span>
                        <small style="font-size:.65rem;color:rgba(255,255,255,.45)">
                            {{ \Carbon\Carbon::parse($peiItem->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($peiItem->year_end)->format('Y') }}
                        </small>
                    </span>
                </a>
            </li>
            @endforeach
        @endrole

        {{-- Sidebar exclusivo para Analista - RIISS --}}
        @role('Analista - RIISS')
            @php $enRiiss = str_contains($path, 'riiss'); @endphp
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('riiss.establecimientos.index') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Inicio RIISS</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#riissMenuAnalista" aria-expanded="{{ $enRiiss ? 'true' : 'false' }}">
                    <i class="material-icons">local_hospital</i>
                    <p>RIISS <b class="caret"></b></p>
                </a>
                <div class="collapse {{ $enRiiss ? 'show' : '' }}" id="riissMenuAnalista">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('riiss/establecimientos*') }}">
                            <a class="nav-link" href="{{ route('riiss.establecimientos.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-hospital" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Establecimientos</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/mis-asignaciones') }}">
                            <a class="nav-link" href="{{ route('riiss.mis-asignaciones') }}">
                                <span class="sidebar-mini"><i class="fa fa-tasks" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Mis Asignaciones</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('riiss/evaluaciones*') }}">
                            <a class="nav-link" href="{{ route('riiss.evaluaciones.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-clipboard-check" style="font-size:.8rem"></i></span>
                                <span class="sidebar-normal">Evaluaciones</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endrole

        {{-- Las siguientes secciones NO deben verse para Analista - RIISS ni Administrador --}}
        @hasanyrole('Alta Gerencia|Participante SIESS')
            @php
                $orgParticipante = \App\Admin\Globales\Organigrama::where('user_id', Auth::id())->first();
                $pendientesParticipante = $orgParticipante
                    ? \App\Models\Estadistica\SiessExtracto::pendientes()->where('direccion_id', $orgParticipante->id)->count()
                    : 0;
            @endphp

            <li class="nav-item">
                <a class="nav-link" href="{{ route('siess.dashboard') }}">
                    <i class="material-icons">home</i>
                    <p>Inicio</p>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('siess.dashboard') }}">
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
                            <a class="nav-link" href="{{ route('siess.dashboard') }}">
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
        @endhasanyrole

        {{-- Mi Perfil (visible para todos) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.edit') }}">
                <i class="material-icons">person</i>
                <p>Mi Perfil</p>
            </a>
        </li>
    </ul>
</div>
