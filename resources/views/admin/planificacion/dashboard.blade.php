@extends('layouts.master')
@section('title', 'Dashboard — Planificación Estratégica')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0"><i class="fa fa-chess mr-2"></i>Panel de Planificación Estratégica</h4>
                <p class="card-category mb-0">Estado del ciclo de planificación — MECIP 2015</p>
            </div>
            <a href="{{ route('home-config.edit') }}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-cog mr-1"></i>Configurar Dashboard
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Planificación</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Selector de Plan Estratégico ── --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <form method="GET" id="formSelectorPei" class="d-flex align-items-center">
                    <label class="font-weight-bold mr-3 mb-0 text-nowrap">
                        <i class="fa fa-file-alt mr-1 text-info"></i> Plan Estratégico:
                    </label>
                    <select name="pei_id" id="selectPei" class="form-control select2" style="max-width:400px">
                        @foreach($peisCorporativos as $pei)
                        <option value="{{ $pei->id }}" {{ $pei->id == $peiSeleccionadoId ? 'selected' : '' }}>
                            {{ strip_tags($pei->name) }}
                            ({{ \Carbon\Carbon::parse($pei->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($pei->year_end)->format('Y') }})
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if($peiActual)
            <div class="col-md-6 text-right">
                <a href="{{ route('pei-profiles.proceso', $peiActual->id) }}" class="btn btn-sm btn-outline-info mr-1">
                    <i class="fa fa-tasks mr-1"></i> Proceso
                </a>
                <a href="{{ route('pei-profiles.show', $peiActual->id) }}" class="btn btn-sm btn-outline-primary mr-1">
                    <i class="fa fa-sitemap mr-1"></i> Árbol del Plan
                </a>
                <a href="{{ route('pei-profiles.dashboard', $peiActual->id) }}" class="btn btn-sm btn-dark">
                    <i class="fa fa-chart-bar mr-1"></i> Tablero
                </a>
            </div>
            @endif
        </div>

        @if(!$peiActual)
        <div class="alert alert-info">
            <i class="fa fa-info-circle mr-2"></i>
            No hay planes estratégicos corporativos registrados.
            <a href="{{ route('pei-profiles.index') }}" class="btn btn-sm btn-info ml-2">Crear Plan</a>
        </div>
        @else

        {{-- ── KPIs principales ── --}}
        <div class="row mb-4">
            @php
            $nombrePlan = strip_tags($peiActual->name ?? '');
            $kpis = [
                ['label'=>'Obj. Estratégicos', 'valor'=>$totalObjetivos,  'sub'=>'en '.$nombrePlan,                             'color'=>'info',    'icon'=>'fa-bullseye',       'url'=>route('pei-profiles.show', $peiActual->id)],
                ['label'=>'Metas',             'valor'=>$totalMetas,      'sub'=>'definidas',                                   'color'=>'success', 'icon'=>'fa-flag',           'url'=>route('pei-profiles.show', $peiActual->id)],
                ['label'=>'Acciones',          'valor'=>$totalAcciones,   'sub'=>$accionesSinResp.' sin responsable',           'color'=>$accionesSinResp>0?'warning':'success', 'icon'=>'fa-rocket', 'url'=>route('pei-profiles.show', $peiActual->id)],
                ['label'=>'Perfiles FODA',     'valor'=>$totalFodaPerfiles,'sub'=>$fodaConsolidados.' consolidados',            'color'=>'danger',  'icon'=>'fa-search',         'url'=>route('foda-list-groups') . '?pei_id=' . $peiActual->id],
                ['label'=>'Proyectos',         'valor'=>$totalProyectos,  'sub'=>$proyectosEjecucion.' en ejecución',           'color'=>'secondary','icon'=>'fa-project-diagram','url'=>route('proyectos-institucionales.index', ['profileId' => $peiActual->id])],

                ['label'=>'Proceso',           'valor'=>'Ver',            'sub'=>'pasos del plan',                              'color'=>'primary', 'icon'=>'fa-tasks',          'url'=>route('pei-profiles.proceso', $peiActual->id)],
            ];
            @endphp
            @foreach($kpis as $k)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <a href="{{ $k['url'] }}" class="text-decoration-none">
                    <div class="card border-left-{{ $k['color'] }} shadow h-100 py-2">
                        <div class="card-body py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-xs font-weight-bold text-{{ $k['color'] }} text-uppercase mb-1">{{ $k['label'] }}</div>
                                    <div class="h3 mb-0 font-weight-bold text-dark">{{ $k['valor'] }}</div>
                                    <div class="text-muted" style="font-size:.72rem">{{ $k['sub'] }}</div>
                                </div>
                                <i class="fa {{ $k['icon'] }} fa-2x text-{{ $k['color'] }} opacity-25 ml-2"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="row mb-4">

            {{-- ── Semáforo global de acciones ── --}}
            <div class="col-md-3 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-traffic-light mr-1"></i> Semáforo de Acciones</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartSemaforo" height="180"></canvas>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><span class="badge badge-success mr-1">●</span> Verde</span>
                                <strong>{{ $semaforo->get('verde', 0) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span><span class="badge badge-warning mr-1">●</span> Amarillo</span>
                                <strong>{{ $semaforo->get('amarillo', 0) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span><span class="badge badge-danger mr-1">●</span> Rojo</span>
                                <strong>{{ $semaforo->get('rojo', 0) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <span><i class="fa fa-circle-notch mr-1"></i> Sin indicador</span>
                                <strong>{{ $totalAcciones - $semaforo->sum() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Cruce FODA por tipo ── --}}
            <div class="col-md-3 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-random mr-1"></i> Cruce de Ambientes</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartCruces" height="180"></canvas>
                        <div class="mt-3">
                            @foreach(['FO'=>'success','FA'=>'info','DO'=>'warning','DA'=>'danger'] as $tipo => $color)
                            <div class="d-flex justify-content-between mb-1">
                                <span><span class="badge badge-{{ $color }} mr-1">{{ $tipo }}</span></span>
                                <strong>{{ $crucesPorTipo->get($tipo, 0) }}</strong>
                            </div>
                            @endforeach
                            <div class="border-top pt-1 mt-1 d-flex justify-content-between">
                                <span class="text-muted">Total estrategias</span>
                                <strong>{{ $totalCruces }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Proyectos por estado ── --}}
            <div class="col-md-3 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-project-diagram mr-1"></i> Proyectos por Estado</h6>
                    </div>
                    <div class="card-body p-0" style="max-height:280px;overflow-y:auto">
                        <table class="table table-sm mb-0">
                            @foreach(\App\Models\Proyectos\ProyectoInstitucional::ESTADOS as $key => $label)
                            @php $cnt = $proyectosPorEstado->get($key, 0); @endphp
                            @if($cnt > 0)
                            <tr>
                                <td style="font-size:.78rem">
                                    <span class="badge {{ \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($key) }}">
                                        {{ $cnt }}
                                    </span>
                                    {{ $label }}
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </table>
                        @if($proyectosSinPei > 0)
                        <div class="alert alert-warning m-2 py-1 mb-0" style="font-size:.78rem">
                            <i class="fa fa-unlink mr-1"></i>
                            {{ $proyectosSinPei }} proyecto(s) sin vincular al PEI
                        </div>
                        @endif
                    </div>
                    <div class="card-footer py-2 text-right">
                        <a href="{{ route('proyectos-institucionales.index', ['profileId' => $peiActual->id]) }}" class="btn btn-sm btn-outline-secondary">
                            Ver todos
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── FODA: análisis con IEA ── --}}
            <div class="col-md-3 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-search mr-1"></i> Análisis FODA</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @php $pctIea = $totalAnalisis > 0 ? round($analisisConIea / $totalAnalisis * 100) : 0; @endphp
                            <div class="h2 font-weight-bold text-{{ $pctIea >= 80 ? 'success' : ($pctIea >= 50 ? 'warning' : 'danger') }}">
                                {{ $pctIea }}%
                            </div>
                            <div class="text-muted" style="font-size:.8rem">con IEA calculado</div>
                            <div class="progress mt-2" style="height:8px">
                                <div class="progress-bar bg-{{ $pctIea >= 80 ? 'success' : ($pctIea >= 50 ? 'warning' : 'danger') }}"
                                     style="width:{{ $pctIea }}%"></div>
                            </div>
                        </div>
                        <table class="table table-sm mb-0">
                            <tr><td class="text-muted">Perfiles FODA</td><td class="text-right font-weight-bold">{{ $totalFodaPerfiles }}</td></tr>
                            <tr><td class="text-muted">Consolidados</td><td class="text-right font-weight-bold">{{ $fodaConsolidados }}</td></tr>
                            <tr><td class="text-muted">Total análisis</td><td class="text-right font-weight-bold">{{ $totalAnalisis }}</td></tr>
                            <tr><td class="text-muted">Con IEA</td><td class="text-right font-weight-bold text-success">{{ $analisisConIea }}</td></tr>
                        </table>
                    </div>
                    <div class="card-footer py-2 text-right">
                        <a href="{{ route('foda-list-groups') }}?pei_id={{ $peiActual->id }}" class="btn btn-sm btn-outline-danger">
                            <i class="fa fa-search mr-1"></i> Ver FODA
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Objetivos Estratégicos del plan seleccionado ── --}}
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fa fa-bullseye mr-1"></i>
                    Objetivos Estratégicos — {{ strip_tags($peiActual->name) }}
                </h6>
                <a href="{{ route('pei-profiles.show', $peiActual->id) }}" class="btn btn-sm btn-outline-primary">Ver árbol completo</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Plan</th>
                                <th>Grupo</th>
                                <th class="text-center">Acciones</th>
                                <th class="text-center">Con indicador</th>
                                <th style="min-width:120px">Cobertura</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peisRecientes as $item)
                            <tr>
                                <td>
                                    <div class="font-weight-bold" style="font-size:.85rem">
                                        {{ strip_tags($item['pei']->name) }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item['pei']->year_start)->format('Y') }} –
                                        {{ \Carbon\Carbon::parse($item['pei']->year_end)->format('Y') }}
                                    </small>
                                </td>
                                <td><small>{{ $item['pei']->group?->name ?? '—' }}</small></td>
                                <td class="text-center"><span class="badge badge-secondary">{{ $item['total_acc'] }}</span></td>
                                <td class="text-center"><span class="badge badge-info">{{ $item['con_semaforo'] }}</span></td>
                                <td>
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar bg-{{ $item['pct'] >= 80 ? 'success' : ($item['pct'] >= 50 ? 'warning' : 'danger') }}"
                                             style="width:{{ $item['pct'] }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $item['pct'] }}%</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pei-profiles.proceso', $item['pei']->id) }}" class="btn btn-info btn-circle" title="Proceso">
                                        <i class="fa fa-tasks"></i>
                                    </a>
                                    <a href="{{ route('pei-profiles.show', $item['pei']->id) }}" class="btn btn-primary btn-circle ml-1" title="Ver PEI">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3"><em>Sin planes registrados</em></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif

    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    // Select2 Plan Estratégico
    $('#selectPei').select2({ width: '400px', minimumResultsForSearch: 5 })
        .on('select2:select', function() { $('#formSelectorPei').submit(); });

    // Semáforo
    new Chart(document.getElementById('chartSemaforo'), {
        type: 'doughnut',
        data: {
            labels: ['Verde', 'Amarillo', 'Rojo', 'Sin indicador'],
            datasets: [{
                data: [
                    {{ $semaforo->get('verde', 0) }},
                    {{ $semaforo->get('amarillo', 0) }},
                    {{ $semaforo->get('rojo', 0) }},
                    {{ $totalAcciones - $semaforo->sum() }}
                ],
                backgroundColor: ['#28a745','#ffc107','#dc3545','#e9ecef'],
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Cruces FODA
    new Chart(document.getElementById('chartCruces'), {
        type: 'bar',
        data: {
            labels: ['FO','FA','DO','DA'],
            datasets: [{
                data: [
                    {{ $crucesPorTipo->get('FO', 0) }},
                    {{ $crucesPorTipo->get('FA', 0) }},
                    {{ $crucesPorTipo->get('DO', 0) }},
                    {{ $crucesPorTipo->get('DA', 0) }},
                ],
                backgroundColor: ['#28a745','#17a2b8','#ffc107','#dc3545'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@stop
