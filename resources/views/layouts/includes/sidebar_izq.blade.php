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
    $enBioestadistica = str_contains($path, 'bioestadistica');
    $enEstadisticas  = $enSiess || $enBioestadistica;
    $enOperativa     = str_contains($path, 'riiss') || str_contains($path, 'patrimony') || str_contains($path, 'activities') || str_contains($path, 'surveys');
    $enCronogramas   = str_contains($path, 'cronogramas');
    $enModulosSiess  = str_contains($path, 'siess/modulos');
    $enFoda          = str_contains($path, 'foda');

    // Helper para marcar link activo (varios patrones; nunca usar || entre llamados).
    $isActive = function (...$routePatterns) {
        foreach ($routePatterns as $pattern) {
            if (str_contains((string) $pattern, '.') && request()->routeIs($pattern)) {
                return 'active';
            }
            if (request()->is($pattern) || request()->is('*/'.$pattern)) {
                return 'active';
            }
        }

        return '';
    };

    $bioDashboard = $isActive('bioestadistica.dashboard', 'bioestadistica', 'bioestadistica/dashboard');
    $bioFormularios = $isActive('bioestadistica.formularios.*', 'bioestadistica.secciones.*', 'bioestadistica.fields.*', 'bioestadistica/formularios*', 'bioestadistica/secciones*', 'bioestadistica/campos*');
    $bioVariables = $isActive('bioestadistica.diccionario.*', 'bioestadistica/diccionario*');
    $bioPendientes = $isActive('bioestadistica.captura.pending', 'bioestadistica/captura/pendientes*');
    $bioSeguimiento = $isActive('bioestadistica.seguimiento.*', 'bioestadistica/seguimiento*');
    $bioCarga = $bioPendientes ? '' : $isActive(
        'bioestadistica.captura.*',
        'bioestadistica.hospitalizacion.*',
        'bioestadistica/captura*',
        'bioestadistica/captura-asignaciones*',
        'bioestadistica/hospitalizacion*'
    );
    $bioIndicadores = $isActive('bioestadistica.indicadores.*', 'bioestadistica/indicadores*');
    $bioReportes = $isActive('bioestadistica.reportes.*', 'bioestadistica/reportes*');
    $bioTableros = $isActive('bioestadistica.dashboards.*', 'bioestadistica/dashboards*');
    $bioImportaciones = $isActive('bioestadistica.importaciones.*', 'bioestadistica/importaciones*');
    $bioAuditoria = $isActive('bioestadistica.auditoria.*', 'bioestadistica/auditoria*');
    $bioGeografia = $isActive('bioestadistica.geografia.*', 'bioestadistica/geografia*');
    $bioEstructura = $isActive('bioestadistica.estructura.*', 'bioestadistica/estructura*');
    $bioClasificaciones = $isActive('bioestadistica.clasificaciones.*', 'bioestadistica/clasificaciones*');
@endphp
@php
    $sysLogoUrl  = \App\Models\HomeConfiguration::getSetting('logo_url');
    $sysSiteName = \App\Models\HomeConfiguration::getSetting('site_name', 'SIPLAN');
@endphp
<div class="logo text-center py-2">
    <a href="{{ route('planificacion-dashboard') }}" class="simple-text logo-normal d-block">
        @if($sysLogoUrl)
            <img src="{{ $sysLogoUrl }}" alt="{{ $sysSiteName }}" style="max-height: 45px; max-width: 85%; object-fit: contain;">
        @else
            {{ $sysSiteName }}
        @endif
    </a>
</div>
<div class="sidebar-wrapper">

    <ul class="nav">
        {{-- Menú exclusivo para Administrador --}}
        @role('Administrador')
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Administrador</p>
                </a>
            </li>
            <li class="nav-item {{ request()->is('admin/soporte/tickets*') ? 'active' : '' }}">
                <a class="nav-link font-weight-bold" href="{{ route('admin.soporte.tickets.index') }}">
                    <i class="material-icons text-danger">confirmation_number</i>
                    <p class="text-danger font-weight-bold">Bandeja de Tickets
                        <span class="badge badge-danger ml-1" id="sidebarTicketPendingBadge" style="display:none">0</span>
                    </p>
                </a>
            </li>
            <li class="nav-item my-2">
                <a class="nav-link text-white font-weight-bold shadow-sm" href="{{ url('/') }}" target="_blank" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 10px; margin: 5px 15px; padding: 10px 15px; transition: all 0.3s ease;" title="Abrir Portal Público Institucional (Nueva Pestaña)">
                    <i class="material-icons text-white mr-2" style="font-size: 1.2rem;">public</i>
                    <p class="text-white font-weight-bold d-inline-block mb-0" style="font-size: 0.88rem;">
                        Ver Sitio Público <i class="fa fa-external-link-alt ml-1" style="font-size: 0.7rem; opacity: 0.85;"></i>
                    </p>
                </a>
            </li>

            {{-- ── DEPARTAMENTO 1: PLANIFICACIÓN (Púrpura Estratégico #4f46e5 / PEI) ── --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#planificacionMenu" aria-expanded="{{ $enPlanificacion ? 'true' : 'false' }}">
                    <i class="material-icons" style="color: #4f46e5 !important; font-weight: bold;">assignment</i>
                    <p class="font-weight-bold">Planificación
                        <span class="badge ml-1" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5; font-size: 0.62rem; border-radius: 8px; padding: 2px 6px; font-weight: 700;">PEI</span>
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $enPlanificacion ? 'show' : '' }}" id="planificacionMenu">
                    <ul class="nav">
                        <li class="nav-item {{ $isActive('planificacion-dashboard') }}">
                            <a class="nav-link" href="{{ route('planificacion-dashboard') }}">
                                <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem; color: #4f46e5;"></i></span>
                                <span class="sidebar-normal">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $isActive('pei-profiles*') }}">
                            <a class="nav-link" href="{{ route('pei-profiles.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-file-alt" style="font-size:.8rem; color: #4f46e5;"></i></span>
                                <span class="sidebar-normal">PEI</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $enFoda ? 'active' : '' }}" data-toggle="collapse" href="#fodaMenu" aria-expanded="{{ $enFoda ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-random" style="font-size:.8rem; color: #4f46e5;"></i></span>
                                <span class="sidebar-normal">FODA <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enFoda ? 'show' : '' }}" id="fodaMenu">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $isActive('foda-list-groups') }}">
                                        <a class="nav-link" href="{{ route('foda-list-groups') }}">
                                            <span class="sidebar-mini"><i class="fa fa-search" style="font-size:.75rem; color: #4f46e5;"></i></span>
                                            <span class="sidebar-normal">Análisis</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('foda-perfiles*') }}">
                                        <a class="nav-link" href="{{ route('foda-perfiles.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-layer-group" style="font-size:.75rem; color: #4f46e5;"></i></span>
                                            <span class="sidebar-normal">Perfiles</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('foda-models*') }}">
                                        <a class="nav-link" href="{{ route('foda-models.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-cubes" style="font-size:.75rem; color: #4f46e5;"></i></span>
                                            <span class="sidebar-normal">Modelos</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Submenú: Marcos & Gestión (Empaquetado) --}}
                        @php
                            $enMarcosGestion = str_contains($path, 'tasks') || str_contains($path, 'pgn') || str_contains($path, 'pei/marcos');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $enMarcosGestion ? 'active' : '' }}" data-toggle="collapse" href="#marcosGestionMenu" aria-expanded="{{ $enMarcosGestion ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-folder-open" style="font-size:.8rem; color: #4f46e5;"></i></span>
                                <span class="sidebar-normal">Marcos & Gestión <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enMarcosGestion ? 'show' : '' }}" id="marcosGestionMenu">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $isActive('tasks*') }}">
                                        <a class="nav-link" href="{{ route('tasks.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-tasks" style="font-size:.75rem; color: #4f46e5;"></i></span>
                                            <span class="sidebar-normal">Tareas</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('pgn*') }}">
                                        <a class="nav-link" href="{{ route('pgn.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-coins" style="font-size:.75rem; color: #4f46e5;"></i></span>
                                            <span class="sidebar-normal">PGN</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('pei/marcos*') }}">
                                        <a class="nav-link" href="{{ route('pei.marcos.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-link" style="font-size:.75rem; color: #4f46e5;"></i></span>
                                            <span class="sidebar-normal">Marcos Referenciales</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Solicitudes de Reorganización Estructural --}}
                        <li class="nav-item {{ $isActive('admin/planificacion/estructura-solicitudes*') }}">
                            <a class="nav-link" href="{{ route('admin.estructura-solicitudes.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-clipboard-list" style="font-size:.8rem; color: #0284c7;"></i></span>
                                <span class="sidebar-normal">Ajustes de Estructura</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endrole

        {{-- Menú específico para Coordinador / Analista de Planificación / Coordinador de Proyectos --}}
        @hasanyrole('Coordinador de Planificación|Coordinación de Planificación|Analista de Planificación|Coordinador de Proyectos|Coordinación de Proyectos')
            <li class="nav-item {{ request()->is('admin/globales/dashboard*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('globales.dashboard') }}">
                    <i class="material-icons" style="color: #4f46e5 !important;">dashboard</i>
                    <p>Dashboard PEI</p>
                </a>
            </li>
        @endhasanyrole

        {{-- ── DEPARTAMENTO 2: PROYECTOS (Verde Esmeralda #10b981 / PMO) ── --}}
        @hasanyrole('Administrador|Super Admin|Coordinador de Proyectos|Coordinación de Proyectos|Coordinador de Planificación')
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#proyectosMenu" aria-expanded="{{ $enProyectos ? 'true' : 'false' }}">
                    <i class="material-icons" style="color: #10b981 !important; font-weight: bold;">account_tree</i>
                    <p class="font-weight-bold">Proyectos
                        <span class="badge ml-1" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 0.62rem; border-radius: 8px; padding: 2px 6px; font-weight: 700;">PMO</span>
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
                                <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem; color: #10b981;"></i></span>
                                <span class="sidebar-normal">Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endhasanyrole

        {{-- ── DEPARTAMENTO 3: ESTADÍSTICAS (Ámbar / Naranja Datos #f59e0b / DATA) ── --}}
        @hasanyrole('Administrador|Analista de Bioestadística|Digitador Bioestadística|Consultor Bioestadística|Auditor Bioestadística')
            @php
                $pendientesSiess = auth()->user()->hasRole('Administrador')
                    ? \App\Models\Estadistica\SiessExtracto::pendientes()->count()
                    : 0;
            @endphp
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#siessMenu" aria-expanded="{{ $enEstadisticas ? 'true' : 'false' }}">
                    <i class="material-icons" style="color: #f59e0b !important; font-weight: bold;">bar_chart</i>
                    <p class="font-weight-bold">Estadísticas
                        <span class="badge ml-1" style="background: rgba(245, 158, 11, 0.12); color: #d97706; font-size: 0.62rem; border-radius: 8px; padding: 2px 6px; font-weight: 700;">DATA</span>
                        @if($pendientesSiess > 0)
                            <span class="badge badge-warning ml-1" style="font-size:.65rem">{{ $pendientesSiess }}</span>
                        @endif
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $enEstadisticas ? 'show' : '' }}" id="siessMenu">
                    <ul class="nav">

                        {{-- ── Submenú 1: Seguridad Social (Color Cascading #f59e0b) ── --}}
                        @role('Administrador')
                        <li class="nav-item">
                            <a class="nav-link {{ $enSiess ? 'active' : '' }}" data-toggle="collapse" href="#seguridadSocialSubMenu" aria-expanded="{{ $enSiess ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-shield-alt" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                <span class="sidebar-normal font-weight-bold" style="color: #334155;">Seguridad Social <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enSiess ? 'show' : '' }}" id="seguridadSocialSubMenu">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $isActive('siess') }}">
                                        <a class="nav-link" href="{{ route('siess.dashboard') }}">
                                            <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                            <span class="sidebar-normal">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/extractos*') }}">
                                        <a class="nav-link" href="{{ route('siess.extractos.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-database" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                            <span class="sidebar-normal">Extractos
                                                @if($pendientesSiess > 0)
                                                    <span class="badge badge-warning" style="font-size:.6rem">{{ $pendientesSiess }}</span>
                                                @endif
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $enModulosSiess ? 'active' : '' }}" data-toggle="collapse" href="#siessModulos" aria-expanded="{{ $enModulosSiess ? 'true' : 'false' }}">
                                            <span class="sidebar-mini"><i class="fa fa-layer-group" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                            <span class="sidebar-normal">Módulos <b class="caret"></b></span>
                                        </a>
                                        <div class="collapse {{ $enModulosSiess ? 'show' : '' }}" id="siessModulos">
                                            <ul class="nav" style="padding-left:10px">
                                                <li class="nav-item {{ $isActive('siess/modulos/aop') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.aop') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">AOP</span>
                                                        <span class="sidebar-normal">Aportes</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ $isActive('siess/modulos/ju') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.ju') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">JU</span>
                                                        <span class="sidebar-normal">Jubilaciones</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ $isActive('siess/modulos/rl') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.rl') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">RL</span>
                                                        <span class="sidebar-normal">Subsidios</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ $isActive('siess/modulos/di') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.di') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">DI</span>
                                                        <span class="sidebar-normal">Inversiones</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ $isActive('siess/modulos/dt') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.dt') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">DT</span>
                                                        <span class="sidebar-normal">Tesorería</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ $isActive('siess/modulos/rh') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.rh') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">RH</span>
                                                        <span class="sidebar-normal">Rec. Humanos</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ $isActive('siess/modulos/cau') }}">
                                                    <a class="nav-link" href="{{ route('siess.modulos.cau') }}">
                                                        <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">CAU</span>
                                                        <span class="sidebar-normal">Atención</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/reportes*') }}">
                                        <a class="nav-link" href="{{ route('siess.reportes.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-file-pdf" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                            <span class="sidebar-normal">Reportes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('siess/eph*') }}">
                                        <a class="nav-link" href="{{ route('siess.eph.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-globe" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                            <span class="sidebar-normal">EPH / INE</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endrole

                        {{-- ── Submenú 2: Bioestadísticas (Color Cascading #f59e0b) ── --}}
                        <li class="nav-item">
                            <a class="nav-link {{ $enBioestadistica ? 'active' : '' }}" data-toggle="collapse" href="#bioestadisticaSubMenu" aria-expanded="{{ $enBioestadistica ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-heartbeat" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                <span class="sidebar-normal font-weight-bold" style="color: #334155;">Bioestadísticas <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enBioestadistica ? 'show' : '' }}" id="bioestadisticaSubMenu">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $bioDashboard }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.dashboard') }}">
                                            <span class="sidebar-mini"><i class="fa fa-tachometer-alt" style="font-size:.8rem; color: #f59e0b;"></i></span>
                                            <span class="sidebar-normal">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioCarga }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.captura.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">CD</span><span class="sidebar-normal">Carga de datos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioPendientes }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.captura.pending') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">PD</span><span class="sidebar-normal">Pendientes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioSeguimiento }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.seguimiento.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">SD</span><span class="sidebar-normal">Seguimiento de datos</span>
                                        </a>
                                    </li>
                                    @can('bio.report.view')
                                    <li class="nav-item {{ $bioReportes }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.reportes.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">RP</span><span class="sidebar-normal">Reportes</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('bio.dashboard.view')
                                    <li class="nav-item {{ $bioTableros }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.dashboards.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">TB</span><span class="sidebar-normal">Dashboards</span>
                                        </a>
                                    </li>
                                    @endcan
                                    <li class="nav-item {{ $bioFormularios }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.formularios.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">FR</span><span class="sidebar-normal">Formularios</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioVariables }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.diccionario.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">VA</span><span class="sidebar-normal">Variables</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioIndicadores }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.indicadores.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">IN</span><span class="sidebar-normal">Indicadores</span>
                                        </a>
                                    </li>
                                    @can('bio.import.view')
                                    <li class="nav-item {{ $bioImportaciones }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.importaciones.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">IM</span><span class="sidebar-normal">Importaciones</span>
                                        </a>
                                    </li>
                                    @endcan
                                    @can('bio.audit.view')
                                    <li class="nav-item {{ $bioAuditoria }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.auditoria.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">AU</span><span class="sidebar-normal">Auditoría</span>
                                        </a>
                                    </li>
                                    @endcan
                                    <li class="nav-item {{ $bioGeografia }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.geografia.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">ES</span><span class="sidebar-normal">Establecimientos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioEstructura }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.estructura.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">DS</span><span class="sidebar-normal">Deptos. y servicios</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $bioClasificaciones }}">
                                        <a class="nav-link" href="{{ route('bioestadistica.clasificaciones.index') }}">
                                            <span class="sidebar-mini" style="color: #d97706; font-weight: bold;">CL</span><span class="sidebar-normal">Clasificaciones</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        @endhasanyrole

        @role('Administrador')
            {{-- ── DEPARTAMENTO 4: GESTIÓN OPERATIVA & SERVICIOS (Teal/Cyan #06b6d4 / OPS) ── --}}
            @php $enRiiss = str_contains($path, 'riiss'); @endphp
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#operativaServiciosMenu" aria-expanded="{{ $enOperativa ? 'true' : 'false' }}">
                    <i class="material-icons" style="color: #06b6d4 !important; font-weight: bold;">settings_suggest</i>
                    <p class="font-weight-bold">Gestión Operativa
                        <span class="badge ml-1" style="background: rgba(6, 182, 212, 0.12); color: #0891b2; font-size: 0.62rem; border-radius: 8px; padding: 2px 6px; font-weight: 700;">OPS</span>
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $enOperativa ? 'show' : '' }}" id="operativaServiciosMenu">
                    <ul class="nav">
                        {{-- Submenú 1: RIISS --}}
                        <li class="nav-item">
                            <a class="nav-link {{ $enRiiss ? 'active' : '' }}" data-toggle="collapse" href="#riissSubMenu" aria-expanded="{{ $enRiiss ? 'true' : 'false' }}">
                                <span class="sidebar-mini"><i class="fa fa-hospital" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal font-weight-bold" style="color: #334155;">RIISS (Red de Salud) <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $enRiiss ? 'show' : '' }}" id="riissSubMenu">
                                <ul class="nav" style="padding-left:10px">
                                    <li class="nav-item {{ $path === 'riiss' ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('riiss.index') }}">
                                            <span class="sidebar-mini"><i class="fa fa-crosshairs" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                            <span class="sidebar-normal">Centro RIISS</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ str_contains($path, 'riiss/configuracion') || str_contains($path, 'riiss/formularios') || str_contains($path, 'riiss/complejidad') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('riiss.configuracion') }}">
                                            <span class="sidebar-mini"><i class="fa fa-cog" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                            <span class="sidebar-normal">Configuración</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ $isActive('riiss/mis-asignaciones') }}">
                                        <a class="nav-link" href="{{ route('riiss.mis-asignaciones') }}">
                                            <span class="sidebar-mini"><i class="fa fa-clipboard-list" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                            <span class="sidebar-normal">Mis Asignaciones</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Patrimonios --}}
                        <li class="nav-item {{ str_contains($path, 'patrimony') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('globales.patrimony-profiles.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-landmark" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal">Patrimonios</span>
                            </a>
                        </li>

                        {{-- Actividades --}}
                        <li class="nav-item {{ str_contains($path, 'activities') && !str_contains($path, 'mis-actividades') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('globales.activities.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-rocket" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal">Actividades</span>
                            </a>
                        </li>

                        {{-- Encuestas --}}
                        <li class="nav-item {{ str_contains($path, 'surveys') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('surveys.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-poll" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal">Encuestas & Evaluación</span>
                            </a>
                        </li>

                        {{-- Variables Globales del Sistema --}}
                        <li class="nav-item {{ str_contains($path, 'configuracion-sistema') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('globales.configuracion-sistema') }}">
                                <span class="sidebar-mini"><i class="fa fa-cogs text-warning" style="font-size:.8rem;"></i></span>
                                <span class="sidebar-normal font-weight-bold" style="color: #d97706;">Variables Globales</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endrole

        {{-- Mis Actividades: un solo item para todos los roles de actividades --}}
        @hasanyrole('Gestor de Actividades|Colaborador de Actividades')
            <li class="nav-item {{ request()->is('mis-actividades') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('globales.activities.mis-actividades') }}">
                    <i class="material-icons">task_alt</i>
                    <p>Mis Actividades</p>
                </a>
            </li>
        @endhasanyrole

        {{-- Sidebar exclusivo para Analista PEI / Analista de Planificación --}}
        @hasanyrole('Analista PEI|Analista de Planificación|Analista')
            @php $enPgnAnalista = str_contains($path, 'pgn') || str_contains($path, 'pei/marcos'); @endphp
            <li class="nav-item {{ $isActive('planificacion-dashboard') }}">
                <a class="nav-link" href="{{ route('planificacion-dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Planificación</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#peiAnalistaMenu" aria-expanded="{{ $enPgnAnalista ? 'true' : 'false' }}">
                    <i class="material-icons">assignment</i>
                    <p>Módulos de Planificación <b class="caret"></b></p>
                </a>
                <div class="collapse {{ $enPgnAnalista ? 'show' : '' }}" id="peiAnalistaMenu">
                    <ul class="nav">
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
            <li class="nav-item {{ request()->is('mis-actividades') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('globales.activities.mis-actividades') }}">
                    <i class="material-icons">task_alt</i>
                    <p>Mis Actividades</p>
                </a>
            </li>
        @endhasanyrole

        {{-- Sidebar exclusivo para Analista de Monitoreo PEI --}}
        @role('Analista de Monitoreo PEI')
            <li class="nav-item {{ request()->is('pei-monitoreo*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pei.monitoreo.dashboard') }}">
                    <i class="material-icons">track_changes</i>
                    <p>Mis Acciones PEI</p>
                </a>
            </li>
        @endrole

        {{-- Sidebar para Coordinador RIISS y Analista RIISS --}}
        @hasanyrole('Coordinador RIISS|Coordinación RIISS|Coordinador - RIISS|Analista - RIISS|Analista RIISS')
            @php $enRiiss = str_contains($path, 'riiss'); @endphp
            <li class="nav-item">
                <a class="nav-link {{ $enRiiss ? 'active' : '' }}" data-toggle="collapse" href="#riissUserMenu" aria-expanded="{{ $enRiiss ? 'true' : 'false' }}">
                    <i class="material-icons" style="color: #06b6d4 !important; font-weight: bold;">local_hospital</i>
                    <p class="font-weight-bold">RIISS (Red de Salud)
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $enRiiss ? 'show' : '' }}" id="riissUserMenu">
                    <ul class="nav" style="padding-left:10px">
                        <li class="nav-item {{ $path === 'riiss' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('riiss.index') }}">
                                <span class="sidebar-mini"><i class="fa fa-crosshairs" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal">Centro RIISS</span>
                            </a>
                        </li>
                        @hasanyrole('Coordinador RIISS|Coordinación RIISS|Coordinador - RIISS|Administrador|Super Admin')
                        <li class="nav-item {{ str_contains($path, 'riiss/configuracion') || str_contains($path, 'riiss/formularios') || str_contains($path, 'riiss/complejidad') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('riiss.configuracion') }}">
                                <span class="sidebar-mini"><i class="fa fa-cog" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal">Configuración</span>
                            </a>
                        </li>
                        @endhasanyrole
                        <li class="nav-item {{ $isActive('riiss/mis-asignaciones') }}">
                            <a class="nav-link" href="{{ route('riiss.mis-asignaciones') }}">
                                <span class="sidebar-mini"><i class="fa fa-clipboard-list" style="font-size:.8rem; color: #06b6d4;"></i></span>
                                <span class="sidebar-normal">Mis Asignaciones</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endhasanyrole

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

        {{-- Mi Perfil (único enlace visible para todos en la parte inferior) --}}
        <li class="nav-item {{ request()->is('perfil*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.profile') }}">
                <i class="material-icons">account_circle</i>
                <p>Mi Perfil & Insignias</p>
            </a>
        </li>
    </ul>
</div>
