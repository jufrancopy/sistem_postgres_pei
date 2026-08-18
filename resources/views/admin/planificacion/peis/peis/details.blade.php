@extends('layouts.master')
@section('title', 'Vista General PEI — ' . strip_tags($root->name))

@section('content')
@php
    $periodoStart = \Carbon\Carbon::parse($root->year_start)->format('Y');
    $periodoEnd   = \Carbon\Carbon::parse($root->year_end)->format('Y');
    $pctSemaforo  = $totalAcciones > 0 ? round((($verdes + $amarillos + $rojos) / $totalAcciones) * 100) : 0;
    $pctVerde     = $totalAcciones > 0 ? round(($verdes    / $totalAcciones) * 100) : 0;
    $pctAmarillo  = $totalAcciones > 0 ? round(($amarillos / $totalAcciones) * 100) : 0;
    $pctRojo      = $totalAcciones > 0 ? round(($rojos     / $totalAcciones) * 100) : 0;
@endphp

{{-- ══ ESTILOS ══════════════════════════════════════════════════════════════ --}}
<style>
.det-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #1a237e 100%);
    border-radius: 0 0 1.5rem 1.5rem;
    padding: 2rem 2rem 1.5rem;
    color: #fff;
    margin-bottom: 1.5rem;
}
.det-kpi {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 1rem;
    padding: .9rem 1.1rem;
    text-align: center;
    transition: background .2s;
}
.det-kpi:hover { background: rgba(255,255,255,.14); }
.det-kpi .kpi-val { font-size: 1.9rem; font-weight: 800; line-height: 1; }
.det-kpi .kpi-lbl { font-size: .68rem; text-transform: uppercase; letter-spacing: .06em; opacity: .75; margin-top: .2rem; }
.det-section-title {
    font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: #64748b; margin-bottom: .75rem;
    display: flex; align-items: center; gap: .4rem;
}
.det-section-title::after {
    content: ''; flex: 1; height: 1px; background: #e2e8f0;
}
.semaforo-bar { height: 10px; border-radius: 5px; overflow: hidden; display: flex; }
.semaforo-bar .seg-verde    { background: #22c55e; }
.semaforo-bar .seg-amarillo { background: #f59e0b; }
.semaforo-bar .seg-rojo     { background: #ef4444; }
.semaforo-bar .seg-sin      { background: #cbd5e1; }
.mvv-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .75rem; border-radius: 2rem; font-size: .75rem; font-weight: 600;
}
.tree-node-axi  { background: linear-gradient(135deg,#1a237e,#283593); color:#fff; border-radius:.5rem; padding:.5rem .9rem; font-size:.85rem; font-weight:700; }
.tree-node-goal { background: #e8eaf6; color:#1a237e; border-radius:.4rem; padding:.35rem .75rem; font-size:.82rem; font-weight:600; border-left:3px solid #3949ab; }
.tree-node-action { background:#fff; border:1px solid #e2e8f0; border-radius:.4rem; padding:.3rem .65rem; font-size:.78rem; border-left:3px solid #64748b; }
.tree-node-action.s-verde    { border-left-color:#22c55e; }
.tree-node-action.s-amarillo { border-left-color:#f59e0b; }
.tree-node-action.s-rojo     { border-left-color:#ef4444; }
.det-card { border: none; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,.07); margin-bottom: 1.25rem; }
.det-card .card-header { border-radius: 1rem 1rem 0 0 !important; border-bottom: 1px solid #f1f5f9; background: #f8fafc; padding: .75rem 1.25rem; }
</style>

{{-- ══ HERO HEADER ══════════════════════════════════════════════════════════ --}}
<div class="det-hero">
    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap:1rem">
        <div>
            <div class="d-flex align-items-center flex-wrap mb-2" style="gap:.5rem">
                <a href="{{ route('pei-profiles.show', $root->id) }}" class="btn btn-sm btn-outline-light py-0 px-2" style="font-size:.72rem">
                    <i class="fa fa-arrow-left mr-1"></i> Volver al Plan
                </a>
                <a href="{{ route('pei-profiles.proceso', $root->id) }}" class="btn btn-sm btn-outline-light py-0 px-2" style="font-size:.72rem">
                    <i class="fa fa-tasks mr-1"></i> Proceso
                </a>

                <a href="{{ route('pei-profiles.details.pdf', $root->id) }}" class="btn btn-sm btn-warning py-0 px-2" style="font-size:.72rem" target="_blank">
                    <i class="fa fa-file-pdf mr-1"></i> PDF
                </a>
            </div>
            <h3 class="font-weight-bold mb-1" style="font-size:1.35rem; line-height:1.3">
                {!! strip_tags($root->name) !!}
            </h3>
            <div class="d-flex flex-wrap align-items-center" style="gap:.5rem; font-size:.8rem; opacity:.85">
                <span><i class="fa fa-calendar mr-1"></i> {{ $periodoStart }} – {{ $periodoEnd }}</span>
                @if($root->dependency)
                    <span><i class="fa fa-building mr-1"></i> {{ $root->dependency->dependency }}</span>
                @endif
                @if($root->nivel_label)
                    <span class="badge badge-light text-dark" style="font-size:.7rem">
                        {{ $niveles['axi'] ?? 'Nivel 1' }} → {{ $niveles['goal'] ?? 'Nivel 2' }} → {{ $niveles['action'] ?? 'Acción' }}
                    </span>
                @endif
            </div>
        </div>
        {{-- MVV pills --}}
        <div class="d-flex flex-wrap" style="gap:.4rem; align-items:flex-start">
            <span class="mvv-pill {{ $tieneMision  ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                <i class="fa fa-{{ $tieneMision  ? 'check' : 'times' }}"></i> Misión
            </span>
            <span class="mvv-pill {{ $tieneVision  ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                <i class="fa fa-{{ $tieneVision  ? 'check' : 'times' }}"></i> Visión
            </span>
            <span class="mvv-pill {{ $tieneValores ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                <i class="fa fa-{{ $tieneValores ? 'check' : 'times' }}"></i> Valores
            </span>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row no-gutters mt-3" style="gap:.6rem; flex-wrap:wrap; display:flex">
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val text-warning">{{ $totalEjes }}</div>
            <div class="kpi-lbl"><i class="fa fa-bullseye mr-1"></i>{{ $niveles['axi'] ?? 'Nivel 1' }}</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val" style="color:#93c5fd">{{ $totalMetas }}</div>
            <div class="kpi-lbl"><i class="fa fa-flag-checkered mr-1"></i>{{ $niveles['goal'] ?? 'Nivel 2' }}</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val text-white">{{ $totalAcciones }}</div>
            <div class="kpi-lbl"><i class="fa fa-rocket mr-1"></i>{{ $niveles['action'] ?? 'Acciones' }}</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val text-success">{{ $verdes }}</div>
            <div class="kpi-lbl">🟢 Verde</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val text-warning">{{ $amarillos }}</div>
            <div class="kpi-lbl">🟡 Amarillo</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val text-danger">{{ $rojos }}</div>
            <div class="kpi-lbl">🔴 Rojo</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val" style="color:#a5b4fc">{{ $accionesConIndicador }}</div>
            <div class="kpi-lbl"><i class="fa fa-ruler-combined mr-1"></i>Con indicador</div>
        </div>
        <div class="det-kpi" style="flex:1 1 100px">
            <div class="kpi-val" style="color:#fde68a">{{ $totalReportes }}</div>
            <div class="kpi-lbl"><i class="fa fa-history mr-1"></i>Reportes</div>
        </div>
    </div>

    {{-- Barra semáforo --}}
    @if($totalAcciones > 0)
    <div class="mt-3">
        <div class="d-flex justify-content-between mb-1" style="font-size:.7rem; opacity:.8">
            <span>Avance semáforo ({{ $pctSemaforo }}% con datos)</span>
            <span>{{ $totalAcciones }} acciones totales</span>
        </div>
        <div class="semaforo-bar">
            <div class="seg-verde"    style="width:{{ $pctVerde }}%"    title="Verde: {{ $verdes }}"></div>
            <div class="seg-amarillo" style="width:{{ $pctAmarillo }}%" title="Amarillo: {{ $amarillos }}"></div>
            <div class="seg-rojo"     style="width:{{ $pctRojo }}%"     title="Rojo: {{ $rojos }}"></div>
            <div class="seg-sin"      style="width:{{ 100 - $pctVerde - $pctAmarillo - $pctRojo }}%" title="Sin datos: {{ $sinDatos }}"></div>
        </div>
    </div>
    @endif
</div>

{{-- ══ CUERPO ════════════════════════════════════════════════════════════════ --}}
<div class="container-fluid px-3">
<div class="row">

{{-- ── Columna izquierda: resumen + gráfico ──────────────────────────────── --}}
<div class="col-lg-4 col-xl-3">

    {{-- Datos del plan --}}
    <div class="det-card card">
        <div class="card-header">
            <div class="det-section-title"><i class="fa fa-info-circle text-primary"></i> Datos del Plan</div>
        </div>
        <div class="card-body p-3" style="font-size:.83rem">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Período</span>
                <strong>{{ $periodoStart }} – {{ $periodoEnd }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">{{ $niveles['axi'] ?? 'Nivel 1' }}</span>
                <span class="badge badge-dark">{{ $totalEjes }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">{{ $niveles['goal'] ?? 'Nivel 2' }}</span>
                <span class="badge badge-primary">{{ $totalMetas }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">{{ $niveles['action'] ?? 'Acciones' }}</span>
                <span class="badge badge-secondary">{{ $totalAcciones }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Con indicador</span>
                <span class="badge badge-info">{{ $accionesConIndicador }} / {{ $totalAcciones }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Ejes con RI</span>
                <span class="badge badge-warning text-dark">{{ $ejesConRI }} / {{ $totalEjes }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Marcos ref.</span>
                <span class="badge badge-success">{{ $totalMarcos }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">MEE (legal)</span>
                <span class="badge badge-light border">{{ $totalMeeMarcos }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">MEE (servicios)</span>
                <span class="badge badge-light border">{{ $totalMeeOfertas }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Responsables únicos</span>
                <span class="badge badge-secondary">{{ $responsablesUnicos }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Participantes</span>
                <span class="badge badge-secondary">{{ $totalMembers }}</span>
            </div>
            @if($presupuestoTotal > 0)
            <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                <span class="text-muted">Presupuesto PGN</span>
                <strong class="text-success" style="font-size:.8rem">Gs. {{ number_format($presupuestoTotal, 0, ',', '.') }}</strong>
            </div>
            @endif
        </div>
    </div>

    {{-- Semáforo detalle --}}
    <div class="det-card card">
        <div class="card-header">
            <div class="det-section-title"><i class="fa fa-traffic-light text-warning"></i> Semáforo de Avance</div>
        </div>
        <div class="card-body p-3">
            @foreach([['verde','#22c55e','🟢',$verdes],['amarillo','#f59e0b','🟡',$amarillos],['rojo','#ef4444','🔴',$rojos],['sin datos','#94a3b8','⚪',$sinDatos]] as [$lbl,$color,$emoji,$cnt])
            <div class="d-flex align-items-center mb-2" style="gap:.5rem">
                <span style="font-size:.85rem">{{ $emoji }}</span>
                <div style="flex:1">
                    <div class="d-flex justify-content-between" style="font-size:.75rem">
                        <span class="text-capitalize">{{ $lbl }}</span>
                        <strong>{{ $cnt }}</strong>
                    </div>
                    <div class="progress mt-1" style="height:5px; border-radius:3px">
                        <div class="progress-bar" style="width:{{ $totalAcciones > 0 ? round($cnt/$totalAcciones*100) : 0 }}%; background:{{ $color }}"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Gráfico pie --}}
    <div class="det-card card">
        <div class="card-header">
            <div class="det-section-title"><i class="fa fa-chart-pie text-info"></i> Acciones por Dependencia</div>
        </div>
        <div class="card-body p-2">
            <div id="piechart" style="height:220px"></div>
        </div>
    </div>

    {{-- Grupos de trabajo --}}
    @if($root->group)
    <div class="det-card card">
        <div class="card-header">
            <div class="det-section-title"><i class="fa fa-users text-secondary"></i> Grupos de Trabajo</div>
        </div>
        <div class="card-body p-3">
            @foreach($root->group->descendants as $grp)
            <div class="mb-1">
                <button class="btn btn-sm btn-outline-secondary btn-block text-left py-1 px-2"
                        data-toggle="collapse" data-target="#grp_{{ $grp->id }}" style="font-size:.78rem">
                    <i class="fa fa-users mr-1"></i> {{ $grp->name }}
                    <span class="badge badge-secondary float-right">{{ $grp->members->count() }}</span>
                </button>
                <div class="collapse" id="grp_{{ $grp->id }}">
                    <div class="pl-2 pt-1">
                        @foreach($grp->members as $m)
                        <span class="badge badge-light border d-block mb-1 text-left" style="font-size:.72rem">
                            <i class="fa fa-user mr-1 text-muted"></i>{{ $m->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>{{-- /col izquierda --}}

{{-- ── Columna derecha: árbol jerárquico ─────────────────────────────────── --}}
<div class="col-lg-8 col-xl-9">

    <div class="det-card card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="det-section-title mb-0"><i class="fa fa-sitemap text-primary"></i> Estructura Jerárquica del Plan</div>
            <div class="d-flex" style="gap:.4rem">
                <button class="btn btn-sm btn-outline-secondary py-0 px-2" id="btnExpandAll" style="font-size:.72rem">
                    <i class="fa fa-expand-alt mr-1"></i> Expandir todo
                </button>
                <button class="btn btn-sm btn-outline-secondary py-0 px-2" id="btnCollapseAll" style="font-size:.72rem">
                    <i class="fa fa-compress-alt mr-1"></i> Colapsar todo
                </button>
            </div>
        </div>
        <div class="card-body p-3">

            @foreach($profile as $matriz)
            @foreach($matriz->children->sortBy('order_item') as $axi)
            @php
                $totalAccAxi = $axi->descendants()->where('level','action')->count();
            @endphp
            <div class="mb-3">
                {{-- Eje / Nivel 1 --}}
                <div class="tree-node-axi d-flex align-items-center justify-content-between mb-1"
                     data-toggle="collapse" data-target="#tree_axi_{{ str_replace('-','',$axi->id) }}"
                     style="cursor:pointer">
                    <div>
                        <span class="badge badge-warning text-dark mr-2" style="font-size:.65rem">{{ $niveles['axi'] ?? 'Nivel 1' }}</span>
                        {!! strip_tags($axi->name) !!}
                    </div>
                    <div class="d-flex align-items-center" style="gap:.4rem; flex-shrink:0">
                        <span class="badge badge-light text-dark" style="font-size:.65rem" title="Acciones">
                            <i class="fa fa-rocket mr-1"></i>{{ $totalAccAxi }}
                        </span>
                        @if($axi->resultado_intermedio)
                        <span class="badge badge-info" style="font-size:.62rem" title="Resultado Intermedio">RI</span>
                        @endif
                        <i class="fa fa-chevron-down" style="font-size:.7rem; opacity:.7"></i>
                    </div>
                </div>

                <div class="collapse show" id="tree_axi_{{ str_replace('-','',$axi->id) }}">
                    <div class="pl-3">
                        @foreach($axi->children->sortBy('order_item') as $goal)
                        <div class="mb-2">
                            {{-- Meta / Nivel 2 --}}
                            <div class="tree-node-goal d-flex align-items-center justify-content-between mb-1"
                                 data-toggle="collapse" data-target="#tree_goal_{{ str_replace('-','',$goal->id) }}"
                                 style="cursor:pointer">
                                <div>
                                    <span class="badge badge-primary mr-1" style="font-size:.6rem">{{ $niveles['goal'] ?? 'Nivel 2' }}</span>
                                    {!! strip_tags($goal->name) !!}
                                </div>
                                <span class="badge badge-secondary ml-2" style="font-size:.6rem; flex-shrink:0">
                                    {{ $goal->children->count() }} acc.
                                </span>
                            </div>

                            <div class="collapse show" id="tree_goal_{{ str_replace('-','',$goal->id) }}">
                                <div class="pl-3">
                                    @foreach($goal->children->sortBy('order_item') as $action)
                                    @php
                                        $sc = $action->semaforo ?? 'sin-datos';
                                        $scClass = in_array($sc,['verde','amarillo','rojo']) ? 's-'.$sc : '';
                                        $scEmoji = ['verde'=>'🟢','amarillo'=>'🟡','rojo'=>'🔴'][$sc] ?? '⚪';
                                    @endphp
                                    <div class="tree-node-action {{ $scClass }} mb-1 d-flex align-items-start justify-content-between">
                                        <div style="flex:1; min-width:0">
                                            <span class="text-muted mr-1" style="font-size:.65rem">#{{ $action->order_item }}</span>
                                            <span style="font-size:.78rem">{!! strip_tags($action->name) !!}</span>
                                            @if($action->responsibles->count())
                                            <div class="mt-1 d-flex flex-wrap" style="gap:.2rem">
                                                @foreach($action->responsibles as $r)
                                                <span class="badge badge-light border" style="font-size:.62rem">
                                                    <i class="fa fa-user mr-1 text-muted"></i>{{ \Illuminate\Support\Str::limit($r->dependency, 35) }}
                                                </span>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column align-items-end ml-2" style="gap:.2rem; flex-shrink:0">
                                            <span style="font-size:.8rem">{{ $scEmoji }}</span>
                                            @if($action->indicador)
                                            <span class="badge badge-light border text-primary" style="font-size:.6rem" title="{{ $action->indicador->nombre }}">
                                                <i class="fa fa-ruler-combined mr-1"></i>{{ $action->indicador->codigoCompleto() }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach

        </div>
    </div>

</div>{{-- /col derecha --}}
</div>{{-- /row --}}
</div>{{-- /container --}}

@stop

@section('scripts')
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
$(function() {

    // ── Pie chart Google Charts ──────────────────────────────────────────────
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(function() {
        var data = google.visualization.arrayToDataTable([
            ['Dependencia', 'Acciones'],
            @foreach($responsiblesActionsCount as $respId => $cnt)
            @php $dep = \App\Admin\Globales\Organigrama::find($respId); @endphp
            @if($dep)
            ['{{ addslashes(\Illuminate\Support\Str::limit($dep->dependency, 40)) }}', {{ $cnt }}],
            @endif
            @endforeach
        ]);
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, {
            legend: { position: 'bottom', textStyle: { fontSize: 11 } },
            chartArea: { width: '90%', height: '75%' },
            pieHole: 0.35,
            tooltip: { textStyle: { fontSize: 12 } },
        });
    });

    // ── Expandir / Colapsar todo ─────────────────────────────────────────────
    $('#btnExpandAll').on('click', function() {
        $('[id^="tree_axi_"], [id^="tree_goal_"]').collapse('show');
    });
    $('#btnCollapseAll').on('click', function() {
        $('[id^="tree_axi_"], [id^="tree_goal_"]').collapse('hide');
    });

    // ── Rotar chevron al colapsar/expandir ───────────────────────────────────
    $(document).on('show.bs.collapse', '[id^="tree_axi_"]', function() {
        $('[data-target="#' + this.id + '"] .fa-chevron-down').css('transform','rotate(0deg)');
    });
    $(document).on('hide.bs.collapse', '[id^="tree_axi_"]', function() {
        $('[data-target="#' + this.id + '"] .fa-chevron-down').css('transform','rotate(-90deg)');
    });
});
</script>
@include('admin.planificacion.peis.peis.partials.chat_drawer')
@stop
