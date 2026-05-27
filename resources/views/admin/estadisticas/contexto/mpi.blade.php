@extends('layouts.master')
@section('title', 'MPI 2024 — Pobreza Multidimensional')

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title"><i class="fa fa-layer-group mr-2"></i>MPI 2024 — Índice de Pobreza Multidimensional</h4>
        <p class="card-category">DGEEC Paraguay · 15 indicadores en 4 dimensiones · Brecha de cobertura IPS directa</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siess.contexto.index') }}">Contexto Nacional</a></li>
            <li class="breadcrumb-item active">MPI 2024</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Procesar dataset ── --}}
        @if($datasets->isNotEmpty())
        <div class="alert alert-light border mb-3">
            <h6 class="font-weight-bold"><i class="fa fa-cogs mr-1"></i> Procesar dataset MPI</h6>
            @foreach($datasets as $ds)
            <form action="{{ route('siess.contexto.mpi.procesar', $ds->id) }}" method="POST" class="d-inline-block mr-2 mb-1">
                @csrf
                <input type="hidden" name="anio" value="{{ $ds->anio }}">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa fa-play mr-1"></i> Procesar "{{ Str::limit($ds->titulo, 30) }}" ({{ $ds->total_filas }} filas)
                </button>
            </form>
            @endforeach
        </div>
        @else
        <div class="alert alert-warning mb-3">
            <i class="fa fa-info-circle mr-1"></i>
            No hay datasets MPI cargados.
            <a href="{{ route('siess.eph.create') }}" class="alert-link">Cargar el archivo MPI2024_fin.csv</a>
            como categoría "IPM".
        </div>
        @endif

        {{-- ── Filtro ── --}}
        <form method="GET" class="row mb-4 align-items-end">
            <div class="col-md-2">
                <select name="anio" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($aniosDisponibles as $a)
                        <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($datos->isEmpty())
        <div class="alert alert-info">Sin datos MPI procesados para {{ $anio }}.</div>
        @else

        {{-- ── Gráficos ── --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-danger">Incidencia MPI por Departamento (%)</h6>
                    </div>
                    <div class="card-body"><canvas id="chartMpi" height="250"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header py-2">
                        <h6 class="m-0 font-weight-bold text-warning">Privaciones de Salud — Brecha IPS (%)</h6>
                    </div>
                    <div class="card-body"><canvas id="chartSalud" height="250"></canvas></div>
                </div>
            </div>
        </div>

        {{-- ── Tabla ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="tablaMpi">
                <thead class="thead-dark">
                    <tr>
                        <th>Departamento</th>
                        <th class="text-center" title="Incidencia: % hogares pobres multidimensionales">H (%)</th>
                        <th class="text-center" title="Intensidad: promedio de privaciones">A (%)</th>
                        <th class="text-center" title="M0 = H × A">MPI M0</th>
                        <th class="text-center text-danger" title="Sin afiliación a seguro — BRECHA IPS">Sin afil.</th>
                        <th class="text-center text-warning" title="Sin acceso a salud">Sin salud</th>
                        <th class="text-center" title="Sin jubilación/pensión">Sin jub.</th>
                        <th class="text-center" title="Combustible inadecuado (leña)">Leña</th>
                        <th class="text-center" title="Sin agua mejorada">Sin agua</th>
                        <th class="text-center">Nivel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $d)
                    <tr class="{{ $d->departamento_codigo == 0 ? 'table-danger font-weight-bold' : '' }}">
                        <td>{{ $d->departamento_nombre }}</td>
                        <td class="text-center">{{ number_format($d->incidencia_h * 100, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->intensidad_a * 100, 1) }}%</td>
                        <td class="text-center">
                            <strong>{{ number_format($d->mpi_m0 * 100, 2) }}%</strong>
                        </td>
                        <td class="text-center text-danger font-weight-bold">
                            {{ number_format($d->d_no_afil * 100, 1) }}%
                        </td>
                        <td class="text-center">{{ number_format($d->d_sin_salud * 100, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->d_jubi_pens * 100, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->d_combus * 100, 1) }}%</td>
                        <td class="text-center">{{ number_format($d->d_agua_mejor * 100, 1) }}%</td>
                        <td class="text-center">
                            @if($d->departamento_codigo > 0)
                            <span class="badge badge-{{ $d->nivel_riesgo === 'alto' ? 'danger' : ($d->nivel_riesgo === 'medio' ? 'warning' : 'success') }}">
                                {{ strtoupper($d->nivel_riesgo) }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="alert alert-info small mt-3">
            <i class="fa fa-info-circle mr-1"></i>
            <strong>Interpretación:</strong> La columna <strong>"Sin afil."</strong> (hh_d_no_afil) es la brecha de cobertura IPS directa.
            Representa el % de hogares donde ningún miembro tiene afiliación a seguro de salud.
            Cruzar con <code>aop_trabajadores</code> por departamento para detectar evasión.
        </div>
        @endif
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    @if(!$datos->isEmpty())
    $.get('{{ route('siess.contexto.chart.mpi') }}?anio={{ $anio }}', function(data) {
        if (!data.labels) return;

        // Gráfico MPI
        new Chart(document.getElementById('chartMpi'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Incidencia MPI (%)',
                    data: data.incidencia.map(v => (v * 100).toFixed(2)),
                    backgroundColor: data.nivel.map(n =>
                        n === 'alto' ? 'rgba(220,53,69,.8)' :
                        n === 'medio' ? 'rgba(255,193,7,.8)' : 'rgba(40,167,69,.8)'
                    ),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v + '%' } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });

        // Gráfico Salud
        new Chart(document.getElementById('chartSalud'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Sin afiliación (Brecha IPS)',
                        data: data.d_no_afil.map(v => (v * 100).toFixed(2)),
                        backgroundColor: 'rgba(220,53,69,.7)',
                    },
                    {
                        label: 'Sin acceso a salud',
                        data: data.d_sin_salud.map(v => (v * 100).toFixed(2)),
                        backgroundColor: 'rgba(255,193,7,.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => v + '%' } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    });
    @endif
});
</script>
@stop
