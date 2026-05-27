@extends('layouts.master')
@section('title', 'Contexto Nacional DGEEC')

@section('content')
<div class="card">
    <div class="card-header card-header-rose">
        <h4 class="card-title"><i class="fa fa-globe-americas mr-2"></i>Contexto Nacional — DGEEC × IPS</h4>
        <p class="card-category">Inteligencia de salud pública: MPI 2024 + Vivienda EPHC + Demografía cruzados con datos internos del IPS</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item active">Contexto Nacional</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Filtro año ── --}}
        <form method="GET" class="row mb-4 align-items-end">
            <div class="col-md-2">
                <label class="small font-weight-bold">Año</label>
                <select name="anio" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($aniosDisponibles as $a)
                        <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                    @if($aniosDisponibles->isEmpty())
                        <option value="{{ $anio }}">{{ $anio }}</option>
                    @endif
                </select>
            </div>
            <div class="col-md-6">
                <a href="{{ route('siess.contexto.mpi') }}" class="btn btn-sm btn-outline-danger mr-1">
                    <i class="fa fa-layer-group mr-1"></i> MPI 2024
                </a>
                <a href="{{ route('siess.contexto.vivienda') }}" class="btn btn-sm btn-outline-warning mr-1">
                    <i class="fa fa-home mr-1"></i> Vivienda EPHC
                </a>
                <a href="{{ route('siess.dgeec.index') }}" class="btn btn-sm btn-outline-info mr-1">
                    <i class="fa fa-users mr-1"></i> Demografía / PEA
                </a>
                <a href="{{ route('siess.eph.create') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-upload mr-1"></i> Cargar datos
                </a>
            </div>
        </form>

        {{-- ── Estado de carga ── --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <div class="card border-left-info shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-info text-uppercase font-weight-bold">Datasets EPH cargados</div>
                        <div class="h3 font-weight-bold mb-0">{{ $kpis['datasets_cargados'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card border-left-{{ $kpis['mpi_registros'] > 0 ? 'success' : 'secondary' }} shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-uppercase font-weight-bold {{ $kpis['mpi_registros'] > 0 ? 'text-success' : 'text-secondary' }}">
                            MPI {{ $anio }}
                        </div>
                        <div class="h3 font-weight-bold mb-0">
                            {{ $kpis['mpi_registros'] > 0 ? $kpis['mpi_registros'] . ' dptos' : '—' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card border-left-{{ $kpis['vivienda_registros'] > 0 ? 'success' : 'secondary' }} shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-uppercase font-weight-bold {{ $kpis['vivienda_registros'] > 0 ? 'text-success' : 'text-secondary' }}">
                            Vivienda {{ $anio }}
                        </div>
                        <div class="h3 font-weight-bold mb-0">
                            {{ $kpis['vivienda_registros'] > 0 ? $kpis['vivienda_registros'] . ' dptos' : '—' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card border-left-{{ $kpis['demografia_registros'] > 0 ? 'success' : 'secondary' }} shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-uppercase font-weight-bold {{ $kpis['demografia_registros'] > 0 ? 'text-success' : 'text-secondary' }}">
                            Demografía {{ $anio }}
                        </div>
                        <div class="h3 font-weight-bold mb-0">
                            {{ $kpis['demografia_registros'] > 0 ? $kpis['demografia_registros'] . ' dptos' : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($kpis['mpi_registros'] == 0 && $kpis['vivienda_registros'] == 0 && $kpis['demografia_registros'] == 0)
        <div class="alert alert-info">
            <h5><i class="fa fa-info-circle mr-2"></i>Sin datos procesados para {{ $anio }}</h5>
            <p class="mb-2">Para ver el mapa de riesgo sanitario necesitás procesar al menos uno de estos datasets:</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-left-danger p-2">
                        <strong>1. MPI 2024</strong><br>
                        <small>Subí el archivo <code>49937-MPI2024_fin.csv</code> como categoría "IPM"</small><br>
                        <a href="{{ route('siess.eph.create') }}" class="btn btn-sm btn-danger mt-1">Cargar MPI</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-warning p-2">
                        <strong>2. Vivienda EPHC</strong><br>
                        <small>Subí el CSV de Vivienda como categoría "Vivienda"</small><br>
                        <a href="{{ route('siess.eph.create') }}" class="btn btn-sm btn-warning mt-1">Cargar Vivienda</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-info p-2">
                        <strong>3. Población EPHC</strong><br>
                        <small>Subí el CSV de Población como categoría "Población"</small><br>
                        <a href="{{ route('siess.eph.create') }}" class="btn btn-sm btn-info mt-1">Cargar Población</a>
                    </div>
                </div>
            </div>
        </div>
        @else

        {{-- ── KPIs Total País ── --}}
        @if($mpiPais || $viviendaPais || $demoPais)
        <div class="row mb-4">
            <div class="col-12 mb-2">
                <h6 class="text-muted font-weight-bold text-uppercase" style="font-size:.75rem">
                    <i class="fa fa-globe mr-1"></i> Total País — {{ $anio }}
                </h6>
            </div>
            @if($mpiPais)
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card border-left-danger shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-danger text-uppercase font-weight-bold">MPI (M0)</div>
                        <div class="h4 font-weight-bold mb-0">{{ number_format($mpiPais->mpi_m0 * 100, 1) }}%</div>
                        <small class="text-muted">Pobreza multidimensional</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card border-left-danger shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-danger text-uppercase font-weight-bold">Sin afiliación</div>
                        <div class="h4 font-weight-bold mb-0">{{ number_format($mpiPais->d_no_afil * 100, 1) }}%</div>
                        <small class="text-muted">Brecha IPS directa</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card border-left-warning shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-warning text-uppercase font-weight-bold">Sin acceso salud</div>
                        <div class="h4 font-weight-bold mb-0">{{ number_format($mpiPais->d_sin_salud * 100, 1) }}%</div>
                        <small class="text-muted">Demanda potencial</small>
                    </div>
                </div>
            </div>
            @endif
            @if($viviendaPais)
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card border-left-warning shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-warning text-uppercase font-weight-bold">Cocina con leña</div>
                        <div class="h4 font-weight-bold mb-0">{{ number_format($viviendaPais->pct_cocina_lena, 1) }}%</div>
                        <small class="text-muted">Riesgo EPOC/asma</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card border-left-info shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-info text-uppercase font-weight-bold">Sin agua potable</div>
                        <div class="h4 font-weight-bold mb-0">{{ number_format($viviendaPais->pct_sin_agua_potable, 1) }}%</div>
                        <small class="text-muted">Riesgo EDAs</small>
                    </div>
                </div>
            </div>
            @endif
            @if($demoPais)
            <div class="col-md-2 col-sm-6 mb-2">
                <div class="card border-left-success shadow py-2">
                    <div class="card-body py-1">
                        <div class="text-xs text-success text-uppercase font-weight-bold">Penetración IPS</div>
                        <div class="h4 font-weight-bold mb-0">{{ number_format($demoPais->tasa_penetracion, 1) }}%</div>
                        <small class="text-muted">Aportantes / PEA</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ── Gráfico Score de Riesgo ── --}}
        <div class="card shadow mb-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fa fa-fire mr-1"></i> Score de Riesgo Sanitario por Departamento
                </h6>
                <small class="text-muted">Pondera: Sin afiliación (30%) + Sin agua (20%) + Leña (20%) + MPI (30%)</small>
            </div>
            <div class="card-body">
                <canvas id="chartRiesgo" height="100"></canvas>
            </div>
        </div>

        {{-- ── Tabla Mapa de Riesgo ── --}}
        @if($mapaRiesgo->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Departamento</th>
                        <th class="text-center">MPI (M0)</th>
                        <th class="text-center">Sin afiliación</th>
                        <th class="text-center">Sin acceso salud</th>
                        <th class="text-center">Sin jubilación</th>
                        <th class="text-center">Nivel riesgo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mapaRiesgo as $d)
                    <tr>
                        <td class="font-weight-bold">{{ $d->departamento_nombre }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $d->nivel_riesgo === 'alto' ? 'danger' : ($d->nivel_riesgo === 'medio' ? 'warning' : 'success') }}">
                                {{ number_format($d->mpi_m0 * 100, 1) }}%
                            </span>
                        </td>
                        <td class="text-center">{{ number_format($d->d_no_afil * 100, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->d_sin_salud * 100, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->d_jubi_pens * 100, 1) }}%</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $d->nivel_riesgo === 'alto' ? 'danger' : ($d->nivel_riesgo === 'medio' ? 'warning' : 'success') }}">
                                {{ strtoupper($d->nivel_riesgo) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @endif {{-- fin if datos --}}
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    @if($kpis['mpi_registros'] > 0 || $kpis['vivienda_registros'] > 0)
    $.get('{{ route('siess.contexto.chart.riesgo') }}?anio={{ $anio }}', function(data) {
        if (!data.labels || data.labels.length === 0) return;

        new Chart(document.getElementById('chartRiesgo'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Score Riesgo Sanitario',
                    data: data.score,
                    backgroundColor: data.score.map(s =>
                        s >= 15 ? 'rgba(220,53,69,.8)' :
                        s >= 8  ? 'rgba(255,193,7,.8)' : 'rgba(40,167,69,.8)'
                    ),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v.toFixed(1) } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    });
    @endif
});
</script>
@stop
