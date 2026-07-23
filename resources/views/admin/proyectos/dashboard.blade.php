@extends('layouts.master')
@section('title', 'Dashboard — Proyectos')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-project-diagram mr-2"></i>Dashboard de Proyectos Institucionales</h4>
        <p class="card-category">Proyectos agrupados por Plan Estratégico</p>
    </div>

    <div class="card-body">

        {{-- ── KPIs globales ── --}}
        <div class="row mb-4">
            @php $kpis = [
                ['label'=>'Total Proyectos',      'valor'=>$total,       'color'=>'primary',   'icon'=>'fa-folder'],
                ['label'=>'Activos',              'valor'=>$activos,     'color'=>'info',      'icon'=>'fa-play-circle'],
                ['label'=>'En Ejecución',         'valor'=>$enEjecucion, 'color'=>'success',   'icon'=>'fa-rocket'],
                ['label'=>'Finalizados',          'valor'=>$finalizados, 'color'=>'secondary', 'icon'=>'fa-check-double'],
                ['label'=>'Presupuesto Aprobado', 'valor'=>'Gs. '.number_format($presupuestoTotal,0,',','.'), 'color'=>'warning', 'icon'=>'fa-coins'],
                ['label'=>'% Ejecutado',          'valor'=>$pctEjecucionPres.'%', 'color'=>$pctEjecucionPres>80?'danger':'success', 'icon'=>'fa-chart-pie'],
            ]; @endphp
            @foreach($kpis as $k)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-{{ $k['color'] }} shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-{{ $k['color'] }} text-uppercase mb-1">{{ $k['label'] }}</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $k['valor'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Proyectos por Plan PEI ── --}}
        @forelse($planesPei as $plan)
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center py-2"
                 style="border-left:4px solid #1976d2">
                <div>
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fa fa-file-alt text-primary mr-2"></i>
                        {{ strip_tags($plan->name) }}
                    </h6>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($plan->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($plan->year_end)->format('Y') }}
                        @if($plan->dependency) · {{ $plan->dependency->dependency }} @endif
                    </small>
                </div>
                <div class="d-flex align-items-center" style="gap:.5rem">
                    <span class="badge badge-primary">{{ $plan->total_proyectos }} proyectos</span>
                    <span class="badge badge-success">{{ $plan->en_ejecucion }} en ejecución</span>
                    <span class="badge badge-secondary">{{ $plan->finalizados }} finalizados</span>
                    @if($plan->solicitudes > 0)
                    <span class="badge badge-warning">{{ $plan->solicitudes }} solicitudes</span>
                    @endif
                    <a href="{{ route('proyectos-institucionales.index', $plan->id) }}"
                       class="btn btn-sm btn-outline-primary ml-2">
                        <i class="fa fa-plus mr-1"></i> Nuevo Proyecto
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Acción PEI vinculada</th>
                                <th>Solicitante</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Avance</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plan->proyectos as $p)
                            <tr>
                                <td><span class="badge badge-secondary" style="font-size:.7rem">{{ $p->codigo }}</span></td>
                                <td style="max-width:180px;font-size:.83rem">{{ Str::limit($p->nombre, 35) }}</td>
                                <td style="max-width:180px">
                                    @if($p->peiProfile)
                                    <small class="text-muted">{{ Str::limit(strip_tags($p->peiProfile->name), 40) }}</small>
                                    @else
                                    <span class="badge badge-warning">Sin acción</span>
                                    @endif
                                </td>
                                <td style="font-size:.78rem">{{ $p->dependenciaSolicitante?->dependency ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($p->estado) }}" style="font-size:.68rem">
                                        {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($p->estado) }}
                                    </span>
                                </td>
                                <td class="text-center" style="min-width:80px">
                                    <div class="progress" style="height:6px">
                                        <div class="progress-bar bg-{{ $p->avance_pct>=80?'success':($p->avance_pct>=50?'info':'warning') }}"
                                             style="width:{{ $p->avance_pct }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $p->avance_pct }}%</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('proyectos-institucionales.show', $p->id) }}"
                                       class="btn btn-info btn-circle btn-sm" title="Ver">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-2"><em>Sin proyectos</em></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info">
            <i class="fa fa-info-circle mr-1"></i> No hay proyectos vinculados a ningún plan PEI aún.
        </div>
        @endforelse

        {{-- ── Sin plan ── --}}
        @if($sinPlan->count() > 0)
        <div class="card shadow border-left-warning mb-3">
            <div class="card-header py-2">
                <h6 class="mb-0 font-weight-bold text-warning">
                    <i class="fa fa-unlink mr-1"></i> Sin vincular a un Plan ({{ $sinPlan->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr><th>Código</th><th>Nombre</th><th class="text-center">Estado</th><th class="text-center"></th></tr>
                        </thead>
                        <tbody>
                            @foreach($sinPlan as $p)
                            <tr>
                                <td><span class="badge badge-secondary" style="font-size:.7rem">{{ $p->codigo }}</span></td>
                                <td style="font-size:.83rem">{{ Str::limit($p->nombre, 50) }}</td>
                                <td class="text-center">
                                    <span class="badge {{ \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($p->estado) }}" style="font-size:.68rem">
                                        {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($p->estado) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('proyectos-institucionales.show', $p->id) }}" class="btn btn-info btn-circle btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@stop
