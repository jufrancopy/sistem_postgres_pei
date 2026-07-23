@extends('layouts.master')
@section('title', 'Dashboard — Configuraciones Globales')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-cogs mr-2"></i>Panel de Configuraciones Globales</h4>
        <p class="card-category">Estado general del sistema SIPLAN — IPS</p>
    </div>

    <div class="card-body">

        {{-- ── Fila 1: KPIs principales ── --}}
        <div class="row mb-4">
            {{-- Usuarios --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Usuarios</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalUsuarios }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $totalAdmins }} admin · {{ $totalParticipantes }} participantes
                        </div>
                    </div>
                </div>
            </div>
            {{-- Organigramas --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Organigramas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalOrganigramas }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $totalDependencias }} dependencias totales</div>
                    </div>
                </div>
            </div>
            {{-- Establecimientos --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Establecimientos</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalEstablecimientos }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $establecimientos_aop }} con AOP</div>
                    </div>
                </div>
            </div>
            {{-- PEI --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Planes PEI</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalPeis }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $totalObjetivos }} obj. · {{ $totalAcciones }} acciones</div>
                    </div>
                </div>
            </div>
            {{-- SIESS --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">SIESS</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalExtractos }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $extractosAprobados }} aprobados
                            @if($extractosPendientes > 0)
                                · <span class="text-warning font-weight-bold">{{ $extractosPendientes }} pendientes</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- Proyectos --}}
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Proyectos</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalProyectos }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $proyectosEjecucion }} en ejecución</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Fila 2: Módulos de acceso rápido ── --}}
        <div class="row mb-4">

            {{-- Usuarios y Accesos --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0"><i class="fa fa-users mr-2"></i>Usuarios y Accesos</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('globales.users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-user mr-2 text-primary"></i>Usuarios</span>
                                <span class="badge badge-primary badge-pill">{{ $totalUsuarios }}</span>
                            </a>
                            <a href="{{ route('globales.roles.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-shield-alt mr-2 text-info"></i>Roles</span>
                                <span class="badge badge-info badge-pill">{{ $totalRoles }}</span>
                            </a>
                            <a href="{{ route('globales.permisos.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-key mr-2 text-warning"></i>Permisos</span>
                                <span class="badge badge-warning badge-pill">{{ $totalPermisos }}</span>
                            </a>
                            <a href="{{ route('globales.groups.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-layer-group mr-2 text-success"></i>Grupos de Trabajo
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Estructura Institucional --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header bg-info text-white py-2">
                        <h6 class="mb-0"><i class="fa fa-sitemap mr-2"></i>Estructura Institucional</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('globales.organigramas.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-project-diagram mr-2 text-info"></i>Organigramas</span>
                                <span class="badge badge-info badge-pill">{{ $totalOrganigramas }}</span>
                            </a>
                            <a href="{{ route('globales.organigramas.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-hospital mr-2 text-success"></i>Establecimientos de Salud</span>
                                <span class="badge badge-success badge-pill">{{ $totalEstablecimientos }}</span>
                            </a>
                            <a href="{{ route('globales.localities.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-map-marker-alt mr-2 text-danger"></i>Departamentos y Localidades
                            </a>
                            <a href="{{ route('globales.activities.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-rocket mr-2 text-warning"></i>Actividades
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configuración del Sistema --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header bg-secondary text-white py-2">
                        <h6 class="mb-0"><i class="fa fa-cog mr-2"></i>Configuración del Sistema</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('globales.formularios.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-file-alt mr-2 text-primary"></i>Formularios
                            </a>
                            <a href="{{ route('globales.variables.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-sliders-h mr-2 text-info"></i>Variables
                            </a>
                            <a href="{{ route('globales.servicios.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-concierge-bell mr-2 text-success"></i>Servicios
                            </a>
                            <a href="{{ route('globales.patrimony-profiles.index') }}" class="list-group-item list-group-item-action">
                                <i class="fa fa-building mr-2 text-warning"></i>Patrimonios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Fila 3: Establecimientos por tenencia + accesos directos ── --}}
        <div class="row">
            {{-- Establecimientos por tenencia --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-chart-pie mr-1"></i> Establecimientos por Tenencia</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTenencia" height="180"></canvas>
                    </div>
                </div>
            </div>

            {{-- Accesos directos a módulos principales --}}
            <div class="col-md-8 mb-3">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-th mr-1"></i> Acceso Rápido a Módulos</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                            $modulos = [
                                ['url' => route('planificacion-dashboard'),          'icon' => 'fa-chess',          'color' => 'primary',   'label' => 'Planificación',     'sub' => 'FODA · PEI · Riesgos'],
                                ['url' => route('siess.dashboard'),                  'icon' => 'fa-chart-bar',      'color' => 'info',      'label' => 'SIESS',             'sub' => 'Estadísticas · Res. 266/22'],
                                ['url' => route('proyectos-institucionales.all'),  'icon' => 'fa-project-diagram','color' => 'success',   'label' => 'Proyectos',         'sub' => 'Seguimiento y Control'],
                                ['url' => route('globales.organigramas.index'),      'icon' => 'fa-sitemap',        'color' => 'warning',   'label' => 'Organigramas',      'sub' => 'Red de Salud · Estructura'],
                                ['url' => route('surveys.index'),                    'icon' => 'fa-poll',           'color' => 'danger',    'label' => 'Encuestas',         'sub' => 'Formularios · Respuestas'],
                                ['url' => route('globales.activities.index'),        'icon' => 'fa-rocket',         'color' => 'secondary', 'label' => 'Actividades',       'sub' => 'Tareas · Evidencias'],
                            ];
                            @endphp
                            @foreach($modulos as $m)
                            <div class="col-md-4 mb-3">
                                <a href="{{ $m['url'] }}" class="card border-left-{{ $m['color'] }} shadow-sm text-decoration-none h-100">
                                    <div class="card-body py-3 d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fa {{ $m['icon'] }} fa-2x text-{{ $m['color'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark" style="font-size:.9rem">{{ $m['label'] }}</div>
                                            <div class="text-muted" style="font-size:.75rem">{{ $m['sub'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    // Gráfico de establecimientos por tenencia
    var tenenciaData = @json($establecimientos_por_tenencia);
    var labels  = Object.keys(tenenciaData);
    var valores = Object.values(tenenciaData);

    if (labels.length > 0) {
        new Chart(document.getElementById('chartTenencia'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: valores,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                var total = ctx.dataset.data.reduce(function(a,b){return a+b;}, 0);
                                var pct   = Math.round(ctx.parsed / total * 100);
                                return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('chartTenencia').parentElement.innerHTML =
            '<p class="text-center text-muted py-4"><em>Sin establecimientos cargados</em></p>';
    }
});
</script>
@stop
