@extends('layouts.master')
@section('title', 'Dashboard — Proyectos')

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-project-diagram mr-2"></i>Módulo de Proyectos</h4>
        <p class="card-category">Seguimiento, Control y Estándares por Complejidad</p>
    </div>

    <div class="card-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            @php $kpis = [
                ['label'=>'Total Proyectos',    'valor'=>$total,         'sub'=>$activos.' activos',                                    'color'=>'primary',  'icon'=>'fa-folder',        'url'=>route('proyectos-institucionales.index')],
                ['label'=>'En Ejecución',       'valor'=>$enEjecucion,   'sub'=>'proyectos activos',                                    'color'=>'success',  'icon'=>'fa-rocket',        'url'=>route('proyectos-institucionales.index').'?estado=en_ejecucion'],
                ['label'=>'Finalizados',        'valor'=>$finalizados,   'sub'=>'completados',                                          'color'=>'secondary','icon'=>'fa-check-double',  'url'=>route('proyectos-institucionales.index')],
                ['label'=>'Sin vincular PEI',   'valor'=>$sinPei,        'sub'=>'requieren vinculación',                                'color'=>$sinPei>0?'warning':'success', 'icon'=>'fa-unlink', 'url'=>route('proyectos-institucionales.index')],
                ['label'=>'Presupuesto Aprobado','valor'=>'Gs. '.number_format($presupuestoTotal,0,',','.'), 'sub'=>$pctEjecucionPres.'% ejecutado', 'color'=>'info', 'icon'=>'fa-coins', 'url'=>route('proyectos-institucionales.index')],
                ['label'=>'Checklist Completo', 'valor'=>$conChecklistCompleto,'sub'=>'de '.$activos.' activos',                       'color'=>'success',  'icon'=>'fa-tasks',         'url'=>route('proyectos-institucionales.index')],
            ]; @endphp
            @foreach($kpis as $k)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <a href="{{ $k['url'] }}" class="text-decoration-none">
                    <div class="card border-left-{{ $k['color'] }} shadow h-100 py-2">
                        <div class="card-body py-2">
                            <div class="text-xs font-weight-bold text-{{ $k['color'] }} text-uppercase mb-1">{{ $k['label'] }}</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ $k['valor'] }}</div>
                            <div class="text-muted" style="font-size:.72rem">{{ $k['sub'] }}</div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="row mb-4">

            {{-- ── Estado de proyectos ── --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-stream mr-1"></i> Proyectos por Estado</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEstados" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- ── En ejecución con avance ── --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-rocket mr-1 text-success"></i> En Ejecución — Avance</h6>
                    </div>
                    <div class="card-body p-0">
                        @forelse($enEjecucionDetalle as $p)
                        <div class="px-3 py-2 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.82rem;font-weight:500">{{ $p->codigo }}</span>
                                <span class="badge badge-success">{{ $p->avance_pct }}%</span>
                            </div>
                            <div class="text-muted mb-1" style="font-size:.75rem">{{ Str::limit($p->nombre, 40) }}</div>
                            <div class="progress" style="height:6px">
                                <div class="progress-bar bg-{{ $p->avance_pct >= 80 ? 'success' : ($p->avance_pct >= 50 ? 'info' : 'warning') }}"
                                     style="width:{{ $p->avance_pct }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4"><em>Sin proyectos en ejecución</em></div>
                        @endforelse
                    </div>
                    <div class="card-footer py-2 text-right">
                        <a href="{{ route('proyectos-institucionales.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-plus mr-1"></i> Nuevo Proyecto
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── Accesos rápidos ── --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-header py-2">
                        <h6 class="mb-0 font-weight-bold"><i class="fa fa-th mr-1"></i> Módulos de Proyectos</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('proyectos-institucionales.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-project-diagram mr-2 text-primary"></i>Proyectos Institucionales (SCPI)</span>
                                <span class="badge badge-primary badge-pill">{{ $total }}</span>
                            </a>
                            <a href="{{ route('proyectos-epc-home') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-hospital mr-2 text-info"></i>Estándares por Complejidad (EPC)</span>
                                <span class="badge badge-info badge-pill">{{ $totalServicios }}</span>
                            </a>
                            <a href="{{ route('globales.patrimony-profiles.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-building mr-2 text-warning"></i>Gestión de Patrimonios</span>
                                <i class="fa fa-chevron-right text-muted"></i>
                            </a>
                            <a href="{{ route('globales.activities.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-rocket mr-2 text-success"></i>Actividades</span>
                                <i class="fa fa-chevron-right text-muted"></i>
                            </a>
                            <a href="{{ route('risks.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-exclamation-triangle mr-2 text-danger"></i>Gestión de Riesgos</span>
                                <i class="fa fa-chevron-right text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Proyectos recientes ── --}}
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 font-weight-bold"><i class="fa fa-clock mr-1"></i> Proyectos Recientes</h6>
                <a href="{{ route('proyectos-institucionales.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Solicitante</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">PEI</th>
                                <th class="text-center">Checklist</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recientes as $p)
                            <tr>
                                <td><span class="badge badge-secondary">{{ $p->codigo }}</span></td>
                                <td style="max-width:200px">
                                    <div style="font-size:.85rem;font-weight:500">{{ Str::limit($p->nombre, 35) }}</div>
                                    @if($p->fecha_fin_estimada)
                                    <small class="text-muted">Fin: {{ $p->fecha_fin_estimada->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td><small>{{ $p->dependenciaSolicitante?->dependency ?? '—' }}</small></td>
                                <td class="text-center">
                                    <span class="badge {{ \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($p->estado) }}" style="font-size:.7rem">
                                        {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($p->estado) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($p->pei_profile_id)
                                        <span class="badge badge-success"><i class="fa fa-link"></i></span>
                                    @else
                                        <span class="badge badge-warning"><i class="fa fa-unlink"></i></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php $pct = $p->pctChecklist(); @endphp
                                    <div class="progress" style="height:6px;min-width:50px">
                                        <div class="progress-bar bg-{{ $pct>=100?'success':($pct>=50?'warning':'danger') }}"
                                             style="width:{{ $pct }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $pct }}%</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('proyectos-institucionales.show', $p->id) }}" class="btn btn-info btn-circle" title="Ver">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3"><em>Sin proyectos registrados</em></td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
    var estados = @json($porEstado);
    var labels  = Object.keys(estados).map(function(k) {
        var map = {
            'solicitud':'Solicitud','en_analisis':'En Análisis','en_desarrollo':'En Desarrollo',
            'en_homologacion':'Homologación','consulta_gerencias':'Consulta','en_tramite':'En Trámite',
            'aprobado':'Aprobado','en_ejecucion':'En Ejecución','finalizado':'Finalizado',
            'rechazado_docs':'Rechazado Docs','rechazado_tecnico':'Rechazado Técnico'
        };
        return map[k] || k;
    });
    var valores = Object.values(estados);
    var colores = ['#6c757d','#17a2b8','#007bff','#ffc107','#fd7e14','#343a40','#28a745','#20c997','#adb5bd','#dc3545','#e83e8c'];

    new Chart(document.getElementById('chartEstados'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: valores, backgroundColor: colores.slice(0, valores.length) }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } }
            }
        }
    });
});
</script>
@stop
