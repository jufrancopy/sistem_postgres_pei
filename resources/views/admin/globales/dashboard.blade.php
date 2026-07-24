@extends('layouts.master')
@section('title', 'Dashboard — Configuraciones Globales')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0"><i class="fa fa-cogs mr-2"></i>Panel de Configuraciones Globales</h4>
                <p class="card-category mb-0">Estado general del sistema SIPLAN — IPS</p>
            </div>
            <a href="{{ route('home-config.edit') }}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-cog mr-1"></i>Configurar Dashboard
            </a>
        </div>
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
                                ['url' => route('proyectos-dashboard'),  'icon' => 'fa-project-diagram','color' => 'success',   'label' => 'Proyectos',         'sub' => 'Seguimiento y Control'],
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

        {{-- ── Configuración del Dashboard ── --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fa fa-cog mr-2 text-primary"></i>Visibilidad de Módulos en el Sitio Público
                        </h6>
                        <small class="text-muted"><i class="fa fa-bolt mr-1 text-success"></i>Los cambios se aplican al instante</small>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            {{-- FODA --}}
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100 {{ $config->show_foda ? 'border-success' : 'border-secondary' }}" id="card_foda" style="transition:border-color .2s">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center" style="gap:.6rem">
                                                <i class="fa fa-search fa-lg text-danger"></i>
                                                <div>
                                                    <div class="font-weight-bold">Módulo FODA</div>
                                                    <small class="text-muted">Análisis estratégico</small>
                                                </div>
                                            </div>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input cfg-toggle"
                                                       id="sw_foda" data-campo="show_foda"
                                                       {{ $config->show_foda ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sw_foda"></label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-muted text-uppercase" style="font-size:.68rem">Perfil FODA a mostrar</label>
                                            <select id="sel_foda_profile" class="form-control form-control-sm cfg-select" data-campo="foda_profile_id" style="width:100%">
                                                <option value="">— Seleccionar perfil —</option>
                                                @foreach($fodaPerfiles as $fp)
                                                <option value="{{ $fp->id }}" {{ $config->foda_profile_id == $fp->id ? 'selected' : '' }}>
                                                    {{ $fp->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- PEI --}}
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100 {{ $config->show_pei ? 'border-success' : 'border-secondary' }}" id="card_pei" style="transition:border-color .2s">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center" style="gap:.6rem">
                                                <i class="fa fa-chart-line fa-lg text-info"></i>
                                                <div>
                                                    <div class="font-weight-bold">Módulo PEI</div>
                                                    <small class="text-muted">Plan Estratégico</small>
                                                </div>
                                            </div>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input cfg-toggle"
                                                       id="sw_pei" data-campo="show_pei"
                                                       {{ $config->show_pei ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sw_pei"></label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="small font-weight-bold text-muted text-uppercase" style="font-size:.68rem">Plan PEI a mostrar</label>
                                            <select id="sel_pei_profile" class="form-control form-control-sm cfg-select" data-campo="pei_profile_id" style="width:100%">
                                                <option value="">— Seleccionar plan —</option>
                                                @foreach($peiPerfiles as $pp)
                                                <option value="{{ $pp->id }}" {{ $config->pei_profile_id == $pp->id ? 'selected' : '' }}>
                                                    {{ strip_tags($pp->name) }}
                                                    @if($pp->year_start)({{ \Carbon\Carbon::parse($pp->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($pp->year_end)->format('Y') }})@endif
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIISS --}}
                            <div class="col-md-4 mb-3">
                                <div class="card border h-100 {{ $config->show_riiss ? 'border-success' : 'border-secondary' }}" id="card_riiss" style="transition:border-color .2s">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center" style="gap:.6rem">
                                                <i class="fa fa-hospital fa-lg text-primary"></i>
                                                <div>
                                                    <div class="font-weight-bold">Módulo RIISS</div>
                                                    <small class="text-muted">Red de Salud IPS</small>
                                                </div>
                                            </div>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input cfg-toggle"
                                                       id="sw_riiss" data-campo="show_riiss"
                                                       {{ $config->show_riiss ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sw_riiss"></label>
                                            </div>
                                        </div>
                                        <div class="text-muted" style="font-size:.8rem">
                                            <i class="fa fa-info-circle mr-1"></i>
                                            Muestra las últimas evaluaciones de establecimientos de la RIISS.
                                            No requiere selección adicional.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- ── Acceso Rápido QR al Sitio Público ── --}}
                        <div class="mt-4 pt-3 border-top">
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-7 mb-3 mb-md-0">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary text-white p-3 rounded mr-3 d-none d-sm-block shadow-sm">
                                            <i class="fa fa-qrcode fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-weight-bold text-dark mb-1">
                                                <i class="fa fa-globe text-primary mr-1"></i> Acceso Rápido al Sitio Público (Código QR)
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                Escanea este código QR desde cualquier dispositivo móvil o comparte el enlace directo para que los visitantes accedan rápidamente a la portada pública del sistema.
                                            </p>
                                            <div class="d-flex align-items-center flex-wrap" style="gap:.5rem">
                                                <div class="input-group input-group-sm" style="max-width:380px">
                                                    <input type="text" class="form-control bg-light font-weight-bold text-dark" id="public_site_url" value="{{ url('/') }}" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-primary" type="button" id="btn_copy_url" title="Copiar enlace">
                                                            <i class="fa fa-copy mr-1"></i> Copiar
                                                        </button>
                                                    </div>
                                                </div>
                                                <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-external-link-alt mr-1"></i> Abrir Portal
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-5 text-center border-left">
                                    <div class="d-inline-block p-2 bg-white rounded border shadow-sm mb-2">
                                        {!! QrCode::size(125)->margin(1)->generate(url('/')) !!}
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalQrPublic">
                                            <i class="fa fa-expand mr-1"></i> Ampliar QR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Ampliar QR --}}
<div class="modal fade" id="modalQrPublic" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2 bg-primary text-white">
                <h6 class="modal-title font-weight-bold mb-0">
                    <i class="fa fa-qrcode mr-1"></i> QR - Sitio Público
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="p-3 bg-white d-inline-block rounded border shadow-sm mb-3">
                    {!! QrCode::size(240)->margin(1)->generate(url('/')) !!}
                </div>
                <div class="text-dark font-weight-bold small mb-1">{{ url('/') }}</div>
                <small class="text-muted">Escanea con la cámara de tu teléfono móvil</small>
            </div>
            <div class="modal-footer py-2 bg-light justify-content-center">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
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

    // ── Toggles de módulos ────────────────────────────────────────────────────
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Select2 para FODA
    $('#sel_foda_profile').select2({
        placeholder: '— Seleccionar perfil —',
        allowClear: true,
        dropdownParent: $('#card_foda'),
        width: '100%',
    });

    // Select2 para PEI
    $('#sel_pei_profile').select2({
        placeholder: '— Seleccionar plan —',
        allowClear: true,
        dropdownParent: $('#card_pei'),
        width: '100%',
    });

    function guardarCampo(campo, valor) {
        $.ajax({
            url:  '{{ route("home-config.save") }}',
            type: 'PATCH',
            data: { campo: campo, valor: valor },
            success: function(res) {
                toastr.success('Configuración guardada');
            },
            error: function() {
                toastr.error('Error al guardar la configuración.');
            }
        });
    }

    // Toggle ON/OFF
    $('.cfg-toggle').on('change', function() {
        var campo  = $(this).data('campo');
        var activo = $(this).is(':checked');
        var cardId = '#card_' + campo.replace('show_', '');

        $(cardId).toggleClass('border-success', activo).toggleClass('border-secondary', !activo);

        $.ajax({
            url:  '{{ route("home-config.toggle") }}',
            type: 'PATCH',
            data: { campo: campo },
            success: function() {
                toastr.success(activo ? '✅ Módulo activado' : '⛔ Módulo desactivado');
            },
            error: function() { toastr.error('Error al actualizar.'); }
        });
    });

    // Select2 — guardar al cambiar
    $('#sel_foda_profile').on('select2:select select2:clear', function() {
        guardarCampo('foda_profile_id', $(this).val() || null);
    });

    $('#sel_pei_profile').on('select2:select select2:clear', function() {
        guardarCampo('pei_profile_id', $(this).val() || null);
    });

    // Copiar URL del Sitio Público
    $('#btn_copy_url').on('click', function() {
        var urlInput = document.getElementById('public_site_url');
        urlInput.select();
        urlInput.setSelectionRange(0, 99999);
        if (navigator.clipboard) {
            navigator.clipboard.writeText(urlInput.value).then(function() {
                toastr.success('Enlace copiado al portapapeles');
            });
        } else {
            document.execCommand('copy');
            toastr.success('Enlace copiado al portapapeles');
        }
    });
});
</script>
@stop
