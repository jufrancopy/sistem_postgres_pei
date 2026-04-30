@extends('layouts.master')
@section('title', 'Tablero de Monitoreo')

@section('content')

<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Tablero de Monitoreo Estratégico</h4>
        <p class="card-category">{{ strip_tags($profile->name) }}</p>
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles PEI</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.show', $profile->id) }}">{{ strip_tags($profile->name) }}</a></li>
            <li class="breadcrumb-item active">Tablero</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPI Cards ── --}}
        <div class="row" id="kpiGrid">
            <div class="col-12 text-center py-3">
                <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
            </div>
        </div>

        <hr>

        {{-- ── § 1 Semáforo ── --}}
        <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size:.75rem;letter-spacing:.08em;">
            <i class="fa fa-circle mr-1 text-info"></i> Semáforo de Indicadores (Lead / Lag)
        </h6>
        <div class="table-responsive mb-4">
            <table class="table table-hover" id="tableSemaforo">
                <thead class="text-info">
                    <tr>
                        <th>#</th>
                        <th>Acción</th>
                        <th>Tipo</th>
                        <th>Avance</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></td></tr>
                </tbody>
            </table>
        </div>

        {{-- ── § 2 RACI ── --}}
        <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size:.75rem;letter-spacing:.08em;">
            <i class="fa fa-sitemap mr-1 text-info"></i> Matriz RACI — Responsables por Acción
        </h6>
        <div class="table-responsive mb-4">
            <table class="table table-hover" id="tableRaci">
                <thead class="text-info">
                    <tr>
                        <th>#</th>
                        <th>Acción</th>
                        <th>Responsable</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4" class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></td></tr>
                </tbody>
            </table>
        </div>

        {{-- ── § 3 Alertas ── --}}
        <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size:.75rem;letter-spacing:.08em;">
            <i class="fa fa-exclamation-triangle mr-1 text-warning"></i> Alertas de Subejecución Presupuestaria
        </h6>
        <div class="table-responsive mb-4">
            <table class="table table-hover" id="tableAlertas">
                <thead class="text-info">
                    <tr>
                        <th>#</th>
                        <th>Acción</th>
                        <th>% Meta</th>
                        <th>% Presupuesto Ejecutado</th>
                        <th>Diagnóstico</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></td></tr>
                </tbody>
            </table>
        </div>

        {{-- ── § 4 IEA ── --}}
        <h6 class="text-muted text-uppercase font-weight-bold mb-3" style="font-size:.75rem;letter-spacing:.08em;">
            <i class="fa fa-chart-bar mr-1 text-info"></i> Índice de Eficiencia de Activos (IEA) — FODA
        </h6>
        <div class="table-responsive">
            <table class="table table-hover" id="tableIea">
                <thead class="text-info">
                    <tr>
                        <th>#</th>
                        <th>Aspecto FODA</th>
                        <th>Tipo</th>
                        <th>IEA</th>
                        <th>Clasificación</th>
                    </tr>
                </thead>
                <tbody>
                    @php $analisis = $analisisFoda ?? collect(); @endphp
                    @forelse($analisis as $i => $a)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $a->aspecto->name ?? '—' }}</td>
                        <td>
                            @php $tipos = ['Fortaleza'=>'success','Debilidad'=>'danger','Oportunidad'=>'info','Amenaza'=>'warning']; @endphp
                            <span class="badge badge-{{ $tipos[$a->tipo] ?? 'secondary' }}">{{ $a->tipo }}</span>
                        </td>
                        <td><code>{{ number_format($a->iea_valor, 4) }}</code></td>
                        <td>
                            @php $cls = ['fortaleza'=>'success','debilidad'=>'danger','neutro'=>'secondary']; @endphp
                            <span class="badge badge-{{ $cls[$a->iea_clasificacion] ?? 'secondary' }}">
                                {{ ucfirst($a->iea_clasificacion) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Sin análisis FODA con IEA calculado para este grupo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>{{-- card-body --}}
</div>{{-- card --}}

@stop

@section('scripts')
<style>
    .kpi-card { border-radius: 8px; padding: 1.2rem; text-align: center; color: #fff; margin-bottom: 1rem; }
    .kpi-card .kpi-num   { font-size: 2.2rem; font-weight: 700; line-height: 1; }
    .kpi-card .kpi-label { font-size: .72rem; opacity: .9; margin-top: .3rem; text-transform: uppercase; letter-spacing: .05em; }
    .semaforo-dot { display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:5px; vertical-align:middle; }
    .dot-verde    { background:#28a745; box-shadow:0 0 6px rgba(40,167,69,.5); }
    .dot-amarillo { background:#ffc107; box-shadow:0 0 6px rgba(255,193,7,.5); }
    .dot-rojo     { background:#dc3545; box-shadow:0 0 6px rgba(220,53,69,.5); }
    .dot-gris     { background:#6c757d; }
    .raci-pill { display:inline-block; width:24px; height:24px; line-height:24px; border-radius:50%; text-align:center; font-size:.72rem; font-weight:700; color:#fff; }
    .raci-A { background:#dc3545; }
    .raci-R { background:#17a2b8; }
    .raci-C { background:#ffc107; color:#333; }
    .raci-I { background:#6c757d; }
    .progress-wrap { display:flex; align-items:center; gap:8px; min-width:120px; }
    .progress-wrap .progress { flex:1; height:6px; border-radius:3px; margin:0; }
    .alert-row td { background:#fff3cd !important; font-weight:600; }
</style>

<script>
$(function () {
    var profileId = '{{ $profile->id }}';

    var urlSemaforo = '{{ route("pei-profiles.semaforo", ":id") }}'.replace(':id', profileId);
    var urlRaci     = '{{ route("pei-profiles.index") }}/' + profileId + '/actions-list';
    var urlAlertas  = '{{ route("pei-profiles.alertas-presupuestarias", ":id") }}'.replace(':id', profileId);

    // ── helpers ──────────────────────────────────────────────
    function barColor(pct) {
        if (pct >= 85) return '#28a745';
        if (pct >= 50) return '#ffc107';
        return '#dc3545';
    }

    function progressBar(pct) {
        if (pct === null || pct === undefined) return '—';
        var c = barColor(pct);
        return '<div class="progress-wrap">' +
               '<div class="progress"><div class="progress-bar" style="width:' + Math.min(pct,100) + '%;background:' + c + '"></div></div>' +
               '<small style="color:' + c + ';font-weight:600;white-space:nowrap">' + pct + '%</small>' +
               '</div>';
    }

    function semaforoIcon(estado) {
        var map = { verde:'dot-verde', amarillo:'dot-amarillo', rojo:'dot-rojo' };
        var cls = map[estado] || 'dot-gris';
        var label = estado ? (estado.charAt(0).toUpperCase() + estado.slice(1)) : 'Sin datos';
        return '<span class="semaforo-dot ' + cls + '"></span>' + label;
    }

    function raciPill(rol) {
        return '<span class="raci-pill raci-' + rol + '" title="' + rol + '">' + rol + '</span>';
    }

    // ── KPI cards ────────────────────────────────────────────
    function buildKpi(resumen, total, alertas) {
        var grid = $('#kpiGrid');
        grid.empty();
        var cards = [
            { num: total,            label: 'Acciones totales',       bg: 'background:linear-gradient(135deg,#17a2b8,#007bff)' },
            { num: resumen.verde,    label: 'En verde',               bg: 'background:linear-gradient(135deg,#28a745,#20c997)' },
            { num: resumen.amarillo, label: 'En amarillo',            bg: 'background:linear-gradient(135deg,#ffc107,#fd7e14)' },
            { num: resumen.rojo,     label: 'En rojo',                bg: 'background:linear-gradient(135deg,#dc3545,#c82333)' },
            { num: alertas,          label: 'Alertas presupuestarias', bg: 'background:linear-gradient(135deg,#6c757d,#495057)' },
        ];
        $.each(cards, function(_, c) {
            grid.append(
                '<div class="col-6 col-md">' +
                '<div class="kpi-card" style="' + c.bg + '">' +
                '<div class="kpi-num">' + c.num + '</div>' +
                '<div class="kpi-label">' + c.label + '</div>' +
                '</div></div>'
            );
        });
    }

    // ── § 1 Semáforo ─────────────────────────────────────────
    $.get(urlSemaforo, function (data) {
        var tbody = $('#tableSemaforo tbody');
        tbody.empty();

        if (!data.acciones || data.acciones.length === 0) {
            tbody.html('<tr><td colspan="5" class="text-center text-muted py-3">Sin acciones con indicadores registrados.</td></tr>');
            buildKpi({ verde:0, amarillo:0, rojo:0 }, 0, 0);
            return;
        }

        $.each(data.acciones, function (i, a) {
            var tipo = a.tipo_indicador === 'lead'
                ? '<span class="badge badge-info">Lead</span>'
                : '<span class="badge badge-secondary">Lag</span>';
            tbody.append('<tr>' +
                '<td>' + (i+1) + '</td>' +
                '<td>' + a.name + '</td>' +
                '<td>' + tipo + '</td>' +
                '<td>' + progressBar(a.avance_pct) + '</td>' +
                '<td>' + semaforoIcon(a.semaforo) + '</td>' +
            '</tr>');
        });

        // Cargar alertas para completar KPI
        $.get(urlAlertas, function(dataA) {
            buildKpi(data.resumen, data.acciones.length, dataA.total_alertas);
        }).fail(function() {
            buildKpi(data.resumen, data.acciones.length, '?');
        });

    }).fail(function () {
        $('#tableSemaforo tbody').html('<tr><td colspan="5" class="text-danger text-center py-3"><i class="fa fa-exclamation-circle mr-1"></i>Error al cargar semáforo.</td></tr>');
    });

    // ── § 2 RACI ─────────────────────────────────────────────
    $.get(urlRaci, function (data) {
        var tbody = $('#tableRaci tbody');
        tbody.empty();

        if (!data.actions || data.actions.length === 0) {
            tbody.html('<tr><td colspan="4" class="text-center text-muted py-3">Sin acciones registradas.</td></tr>');
            return;
        }

        $.each(data.actions, function (i, action) {
            var nombre = $('<div>').html(action.name).text();
            if (!action.responsibles || action.responsibles.length === 0) {
                tbody.append('<tr><td>' + (i+1) + '</td><td>' + nombre + '</td><td colspan="2" class="text-muted">Sin responsables</td></tr>');
                return;
            }
            $.each(action.responsibles, function (j, resp) {
                var rol = (resp.pivot && resp.pivot.rol) ? resp.pivot.rol : 'R';
                var fila = '<tr>';
                if (j === 0) {
                    fila += '<td rowspan="' + action.responsibles.length + '">' + (i+1) + '</td>';
                    fila += '<td rowspan="' + action.responsibles.length + '">' + nombre + '</td>';
                }
                fila += '<td>' + resp.dependency + '</td>';
                fila += '<td>' + raciPill(rol) + '</td>';
                fila += '</tr>';
                tbody.append(fila);
            });
        });
    }).fail(function () {
        $('#tableRaci tbody').html('<tr><td colspan="4" class="text-danger text-center py-3"><i class="fa fa-exclamation-circle mr-1"></i>Error al cargar RACI.</td></tr>');
    });

    // ── § 3 Alertas ──────────────────────────────────────────
    $.get(urlAlertas, function (data) {
        var tbody = $('#tableAlertas tbody');
        tbody.empty();

        if (data.total_alertas === 0) {
            tbody.html('<tr><td colspan="5" class="text-center text-success py-3"><i class="fa fa-check-circle mr-1"></i>Sin alertas de subejecución.</td></tr>');
            return;
        }

        $.each(data.acciones, function (i, a) {
            tbody.append('<tr class="alert-row">' +
                '<td>' + (i+1) + '</td>' +
                '<td>' + a.name + '</td>' +
                '<td>' + progressBar(a.pct_meta) + '</td>' +
                '<td>' + progressBar(a.pct_presupuesto) + '</td>' +
                '<td><span class="badge badge-warning text-dark"><i class="fa fa-exclamation-triangle mr-1"></i>Subejecución</span></td>' +
            '</tr>');
        });
    }).fail(function () {
        $('#tableAlertas tbody').html('<tr><td colspan="5" class="text-danger text-center py-3"><i class="fa fa-exclamation-circle mr-1"></i>Error al cargar alertas.</td></tr>');
    });
});
</script>
@stop
