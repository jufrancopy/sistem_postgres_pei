@extends('layouts.master')
@section('title', 'Análisis de Brechas IPS vs EPHC')

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title"><i class="fa fa-exclamation-triangle mr-2"></i>Análisis de Brechas — IPS vs EPHC</h4>
        <p class="card-category">Tasa de Penetración Real: Cotizantes IPS internos vs Aportantes estimados por la EPHC</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('siess.dashboard') }}">Estadísticas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('siess.dgeec.index') }}">KPIs DGEEC</a></li>
            <li class="breadcrumb-item active">Análisis de Brechas</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Filtro año ── --}}
        <form method="GET" class="row mb-4 align-items-end">
            <div class="col-md-3">
                <label class="small font-weight-bold">Año</label>
                <select name="anio" class="form-control form-control-sm" onchange="this.form.submit()">
                    @foreach($anios as $a)
                        <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- ── Explicación ── --}}
        <div class="alert alert-light border mb-4">
            <div class="row">
                <div class="col-md-4">
                    <h6 class="font-weight-bold text-success"><i class="fa fa-check-circle mr-1"></i> Normal (brecha ±10%)</h6>
                    <p class="small mb-0">Los cotizantes internos del IPS coinciden con la estimación EPHC.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="font-weight-bold text-danger"><i class="fa fa-arrow-up mr-1"></i> Brecha positiva &gt;10%</h6>
                    <p class="small mb-0">Más cotizantes IPS que los estimados por EPHC. Posibles <strong>cotizantes fantasmas</strong> o doble empleo mal contabilizado.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="font-weight-bold text-warning"><i class="fa fa-arrow-down mr-1"></i> Brecha negativa &gt;10%</h6>
                    <p class="small mb-0">Menos cotizantes IPS que los estimados por EPHC. Posible <strong>evasión</strong> de aportes.</p>
                </div>
            </div>
        </div>

        @if($analisis->isEmpty())
        <div class="alert alert-warning">
            <i class="fa fa-info-circle mr-2"></i>
            No hay datos EPHC procesados para {{ $anio }}.
            <a href="{{ route('siess.eph.index') }}" class="alert-link">Cargar datos EPH</a>
        </div>
        @else

        {{-- ── Gráfico de brechas ── --}}
        <div class="card shadow mb-4">
            <div class="card-header py-2">
                <h6 class="m-0 font-weight-bold">Brecha % por Departamento (IPS interno vs EPHC)</h6>
            </div>
            <div class="card-body">
                <canvas id="chartBrecha" height="120"></canvas>
            </div>
        </div>

        {{-- ── Tabla de análisis ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="tablaBrecha">
                <thead class="thead-dark">
                    <tr>
                        <th>Departamento</th>
                        <th class="text-right">PEA Ocupada<br><small class="font-weight-normal">(EPHC)</small></th>
                        <th class="text-right">Aportantes IPS<br><small class="font-weight-normal">(EPHC estimado)</small></th>
                        <th class="text-right">Cotizantes IPS<br><small class="font-weight-normal">(Sistema interno)</small></th>
                        <th class="text-right">Brecha</th>
                        <th class="text-center">Brecha %</th>
                        <th class="text-center">Penetración EPHC</th>
                        <th class="text-center">Informalidad</th>
                        <th>Diagnóstico</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($analisis as $fila)
                    <tr>
                        <td class="font-weight-bold">{{ $fila['departamento'] }}</td>
                        <td class="text-right">{{ number_format($fila['pea_ocupada'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($fila['aportantes_ephc'], 0, ',', '.') }}</td>
                        <td class="text-right">
                            @if($fila['cotizantes_ips'] > 0)
                                {{ number_format($fila['cotizantes_ips'], 0, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-right {{ $fila['brecha'] > 0 ? 'text-danger' : ($fila['brecha'] < 0 ? 'text-warning' : '') }}">
                            {{ $fila['brecha'] != 0 ? ($fila['brecha'] > 0 ? '+' : '') . number_format($fila['brecha'], 0, ',', '.') : '—' }}
                        </td>
                        <td class="text-center">
                            @php $pct = $fila['pct_brecha']; @endphp
                            @if($fila['cotizantes_ips'] > 0)
                            <span class="badge badge-{{ abs($pct) <= 10 ? 'success' : ($pct > 10 ? 'danger' : 'warning') }}">
                                {{ $pct > 0 ? '+' : '' }}{{ number_format($pct, 1) }}%
                            </span>
                            @else
                            <span class="text-muted small">Sin datos IPS</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $fila['tasa_penetracion_ephc'] >= 50 ? 'success' : ($fila['tasa_penetracion_ephc'] >= 30 ? 'warning' : 'danger') }}">
                                {{ number_format($fila['tasa_penetracion_ephc'], 1) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $fila['tasa_informalidad'] <= 40 ? 'success' : ($fila['tasa_informalidad'] <= 60 ? 'warning' : 'danger') }}">
                                {{ number_format($fila['tasa_informalidad'], 1) }}%
                            </span>
                        </td>
                        <td class="small">{{ $fila['diagnostico'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="alert alert-info small mt-3">
            <i class="fa fa-info-circle mr-1"></i>
            <strong>Nota:</strong> Los datos de "Cotizantes IPS (Sistema interno)" provienen de la tabla
            <code>aop_trabajadores</code>. Conectar con el módulo AOP para comparación en tiempo real.
        </div>

        @endif
    </div>
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(function() {
    @if(!$analisis->isEmpty())
    var data = @json($analisis->values());

    var conDatos = data.filter(d => d.cotizantes_ips > 0);

    if (conDatos.length > 0) {
        new Chart(document.getElementById('chartBrecha'), {
            type: 'bar',
            data: {
                labels: conDatos.map(d => d.departamento),
                datasets: [{
                    label: 'Brecha % (IPS interno vs EPHC)',
                    data: conDatos.map(d => d.pct_brecha),
                    backgroundColor: conDatos.map(d =>
                        Math.abs(d.pct_brecha) <= 10 ? 'rgba(40,167,69,.7)' :
                        d.pct_brecha > 10 ? 'rgba(220,53,69,.7)' : 'rgba(255,193,7,.7)'
                    ),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                var d = conDatos[ctx.dataIndex];
                                return [
                                    'Brecha: ' + (d.pct_brecha > 0 ? '+' : '') + d.pct_brecha.toFixed(1) + '%',
                                    'IPS interno: ' + d.cotizantes_ips.toLocaleString(),
                                    'EPHC estimado: ' + d.aportantes_ephc.toLocaleString(),
                                    d.diagnostico
                                ];
                            }
                        }
                    }
                },
                scales: {
                    y: { ticks: { callback: v => v + '%' } },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    } else {
        document.getElementById('chartBrecha').closest('.card').innerHTML =
            '<div class="card-body text-center text-muted py-4"><i class="fa fa-info-circle mr-2"></i>Conectar datos internos AOP para ver el gráfico de brechas.</div>';
    }
    @endif
});
</script>
@stop
