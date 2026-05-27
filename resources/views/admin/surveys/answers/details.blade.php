@extends('layouts.master')
@section('title', 'Resultados — ' . $survey->name)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-chart-bar mr-2"></i>Resultados de la Encuesta</h4>
        <p class="card-category">{{ $survey->name }}</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('surveys.index') }}">Encuestas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('surveys.show', $survey->id) }}">{{ $survey->name }}</a></li>
            <li class="breadcrumb-item active">Resultados</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs de participación ── --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Preguntas</div>
                        <div class="h3 mb-0 font-weight-bold">{{ count($answersData) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Participantes</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalParticipantes }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completaron</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $totalCompletados }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-left-{{ $pctCompletado >= 80 ? 'success' : ($pctCompletado >= 50 ? 'warning' : 'danger') }} shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">% Completitud</div>
                        <div class="h3 mb-0 font-weight-bold">{{ $pctCompletado }}%</div>
                        <div class="progress mt-1" style="height:4px">
                            <div class="progress-bar bg-{{ $pctCompletado >= 80 ? 'success' : ($pctCompletado >= 50 ? 'warning' : 'danger') }}"
                                 style="width:{{ $pctCompletado }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Resultados por pregunta ── --}}
        @if(empty($answersData))
        <div class="alert alert-info">
            <i class="fa fa-info-circle mr-2"></i>
            No hay respuestas registradas para esta encuesta todavía.
        </div>
        @else

        @foreach($answersData as $i => $data)
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold">
                    <span class="badge badge-secondary mr-2">{{ $i + 1 }}</span>
                    {!! $data['question'] !!}
                </h6>
                <small class="text-muted">{{ $data['total_respuestas'] }} respuesta(s)</small>
            </div>
            <div class="card-body">
                @if(empty($data['options']))
                <p class="text-muted mb-0"><em>Sin opciones configuradas.</em></p>
                @else
                <div class="row">
                    {{-- Barras de resultados --}}
                    <div class="col-md-7">
                        @foreach($data['options'] as $opcion)
                        @php
                            $pct = $data['total_respuestas'] > 0
                                ? round($opcion['count'] / $data['total_respuestas'] * 100) : 0;
                            $colorBar = $opcion['is_correct'] ? 'success' : 'secondary';
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.85rem">
                                    @if($opcion['is_correct'])
                                    <i class="fa fa-check-circle text-success mr-1"></i>
                                    @endif
                                    {{ $opcion['answer'] }}
                                </span>
                                <span class="text-muted" style="font-size:.8rem">
                                    {{ $opcion['count'] }} ({{ $pct }}%)
                                </span>
                            </div>
                            <div class="progress" style="height:10px;border-radius:5px">
                                <div class="progress-bar bg-{{ $colorBar }}"
                                     style="width:{{ $pct }}%;transition:width .6s ease"
                                     title="{{ $pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- Mini gráfico de torta --}}
                    <div class="col-md-5 text-center">
                        <canvas id="chart_q{{ $i }}" height="140"></canvas>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        @endif

    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    @foreach($answersData as $i => $data)
    @if(!empty($data['options']) && $data['total_respuestas'] > 0)
    new Chart(document.getElementById('chart_q{{ $i }}'), {
        type: 'doughnut',
        data: {
            labels: @json(collect($data['options'])->pluck('answer')),
            datasets: [{
                data: @json(collect($data['options'])->pluck('count')),
                backgroundColor: [
                    '#28a745','#dc3545','#ffc107','#17a2b8','#6c757d','#007bff'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 } } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var total = ctx.dataset.data.reduce(function(a,b){return a+b;},0);
                            var pct = total > 0 ? Math.round(ctx.parsed/total*100) : 0;
                            return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
    @endif
    @endforeach
});
</script>
@stop
