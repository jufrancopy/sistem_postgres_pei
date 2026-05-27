@extends('layouts.master')
@section('title', 'DGEEC — KPIs Nacionales')

@section('content')
<div class="card">
    <div class="card-header card-header-warning">
        <h4 class="card-title"><i class="fa fa-chart-bar mr-2"></i>Contexto Nacional DGEEC — KPIs cruzados con IPS</h4>
        <p class="card-category">Datos procesados de la EPHC (INE Paraguay) cruzados con datos internos del IPS</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siess.eph.index') }}">EPH</a></li>
            <li class="breadcrumb-item active">KPIs DGEEC</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Filtros ── --}}
        <form method="GET" action="{{ route('siess.dgeec.index') }}" class="row mb-4 align-items-end">
            <div class="col-md-3">
                <label class="small font-weight-bold">Año</label>
                <select name="anio" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($anios as $a)
                        <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                    @if($anios->isEmpty())
                        <option value="{{ now()->year }}">{{ now()->year }}</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <a href="{{ route('siess.dgeec.brecha') }}?anio={{ $anio }}" class="btn btn-outline-danger btn-sm">
                    <i class="fa fa-exclamation-triangle mr-1"></i> Análisis de Brechas
                </a>
                <a href="{{ route('siess.eph.index') }}" class="btn btn-outline-info btn-sm ml-1">
                    <i class="fa fa-upload mr-1"></i> Cargar datos EPH
                </a>
            </div>
        </form>

        @if($datosEphc->isEmpty())
        {{-- ── Estado vacío ── --}}
        <div class="alert alert-warning">
            <h5><i class="fa fa-info-circle mr-2"></i>Sin datos procesados para {{ $anio }}</h5>
            <p class="mb-2">Para ver los KPIs necesitás:</p>
            <ol>
                <li>Subir un archivo EPHC desde <a href="{{ route('siess.eph.create') }}">EPH → Cargar JSON</a></li>
                <li>Ir al dataset cargado y hacer clic en <strong>"Interpretar con diccionario EPHC"</strong></li>
                <li>Mapear las columnas y procesar</li>
            </ol>
        </div>
        @else

        {{-- ── KPIs Totales País ── --}}
        @php
            $totalPais = $datosEphc->firstWhere('departamento_codigo', 0);
        @endphp
        @if($totalPais)
        <div class="row mb-4">
            <div class="col-12 mb-2">
                <h6 class="text-muted font-weight-bold text-uppercase" style="font-size:.75rem; letter-spacing:.05em">
                    <i class="fa fa-globe mr-1"></i> Total País — {{ $anio }}
                    <small class="text-muted ml-2">Fuente: {{ $totalPais->fuente }} · {{ $totalPais->periodo_referencia }}</small>
                </h6>
            </div>

            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Población Total</div>
                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalPais->poblacion_total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">PEA Ocupada</div>
                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalPais->pea_ocupada, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aportantes IPS</div>
                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalPais->aportantes_ips, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tasa Penetración IPS</div>
                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalPais->tasa_penetracion, 1) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tasa Informalidad</div>
                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalPais->tasa_informalidad, 1) }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Población Pobre</div>
                        <div class="h4 mb-0 font-weight-bold">{{ number_format($totalPais->poblacion_pobre, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Gráficos ── --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-primary">Penetración IPS por Departamento (%)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPenetracion" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-danger">Informalidad Laboral por Departamento (%)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartInformalidad" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tabla por departamento ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="tablaDgeec">
                <thead class="thead-dark">
                    <tr>
                        <th>Departamento</th>
                        <th class="text-right">Población</th>
                        <th class="text-right">PEA Ocupada</th>
                        <th class="text-right">Aportantes IPS</th>
                        <th class="text-right">Informales</th>
                        <th class="text-center">Penetración IPS</th>
                        <th class="text-center">Informalidad</th>
                        <th class="text-right">Pobres</th>
                        <th class="text-center">Área</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datosEphc as $dato)
                    <tr class="{{ $dato->departamento_codigo == 0 ? 'table-primary font-weight-bold' : '' }}">
                        <td>
                            @if($dato->departamento_codigo == 0)
                                <i class="fa fa-globe mr-1"></i>
                            @endif
                            {{ $dato->departamento_nombre }}
                        </td>
                        <td class="text-right">{{ number_format($dato->poblacion_total, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dato->pea_ocupada, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dato->aportantes_ips, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($dato->informalidad_total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php $tp = $dato->tasa_penetracion; @endphp
                            <span class="badge badge-{{ $tp >= 50 ? 'success' : ($tp >= 30 ? 'warning' : 'danger') }}">
                                {{ number_format($tp, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @php $ti = $dato->tasa_informalidad; @endphp
                            <span class="badge badge-{{ $ti <= 40 ? 'success' : ($ti <= 60 ? 'warning' : 'danger') }}">
                                {{ number_format($ti, 1) }}%
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($dato->poblacion_pobre, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge badge-secondary">{{ ucfirst($dato->area) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @endif {{-- fin if datosEphc --}}
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    @if(!$datosEphc->isEmpty())
    var chartData = @json($chartData);

    // Filtrar solo departamentos (excluir total país)
    var depts = chartData.filter(d => d.departamento !== 'Total País');

    // ── Gráfico Penetración ──────────────────────────────────────────────
    new Chart(document.getElementById('chartPenetracion'), {
        type: 'bar',
        data: {
            labels: depts.map(d => d.departamento),
            datasets: [{
                label: 'Tasa Penetración IPS (%)',
                data: depts.map(d => d.tasa_penetracion),
                backgroundColor: depts.map(d =>
                    d.tasa_penetracion >= 50 ? 'rgba(40,167,69,.7)' :
                    d.tasa_penetracion >= 30 ? 'rgba(255,193,7,.7)' : 'rgba(220,53,69,.7)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100,
                    ticks: { callback: v => v + '%' }
                },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // ── Gráfico Informalidad ─────────────────────────────────────────────
    new Chart(document.getElementById('chartInformalidad'), {
        type: 'bar',
        data: {
            labels: depts.map(d => d.departamento),
            datasets: [{
                label: 'Tasa Informalidad (%)',
                data: depts.map(d => d.tasa_informalidad),
                backgroundColor: depts.map(d =>
                    d.tasa_informalidad <= 40 ? 'rgba(40,167,69,.7)' :
                    d.tasa_informalidad <= 60 ? 'rgba(255,193,7,.7)' : 'rgba(220,53,69,.7)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100,
                    ticks: { callback: v => v + '%' }
                },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });
    @endif
});
</script>
@stop
