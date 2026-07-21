@extends('layouts.master')
@section('title', 'Evaluación #' . $evaluacion->id)

@push('styles')
<style>
.gap-item { padding:10px 14px; border-radius:8px; margin-bottom:6px; border:1px solid #e5e7eb; }
.gap-cumple        { border-left:4px solid #22c55e; background:#f0fdf4; }
.gap-no_cumple     { border-left:4px solid #ef4444; background:#fef2f2; }
.gap-no_verificable{ border-left:4px solid #f97316; background:#fff7ed; }
.gap-pendiente     { border-left:4px solid #94a3b8; background:#f8fafc; }
.gap-no_aplica     { border-left:4px solid #e2e8f0; background:#f8fafc; }
.grupo-header { background:#f9fafb; border-radius:8px; padding:10px 14px; margin-bottom:8px; cursor:pointer; }
.pct-ring { width:80px; height:80px; }
#evalTabs .nav-link { color:#343a40 !important; }
#evalTabs .nav-link.active { color:#1a237e !important; font-weight:600; }
#evalTabs .nav-link:hover { color:#1a237e !important; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-danger">
        <h4 class="card-title">
            <i class="fa fa-chart-bar mr-2"></i>Evaluación #{{ $evaluacion->id }}
            — {{ $evaluacion->establecimiento->nombre_oficial ?? '—' }}
        </h4>
        <p class="card-category">
            {{ $evaluacion->fecha_evaluacion?->format('d/m/Y') }}
            @if($evaluacion->evaluador_nombre) · {{ $evaluacion->evaluador_nombre }} @endif
        </p>
    </div>

    <nav class="bg-light px-3 py-2">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('riiss.establecimientos.index') }}">Establecimientos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.evaluaciones.index') }}">Evaluaciones</a></li>
            <li class="breadcrumb-item active">#{{ $evaluacion->id }}</li>
        </ol>
    </nav>

    <div class="card-body">

        @php
            $pct    = $evaluacion->porcentaje_cumplimiento ?? 0;
            $clasif = $evaluacion->clasificacion_resultado;
            $est    = $evaluacion->establecimiento;
            $clasifColor = match($clasif) {
                'CUMPLE'              => 'success',
                'CUMPLE_PARCIALMENTE' => 'warning',
                'NO_CUMPLE'           => 'danger',
                default               => 'secondary',
            };
            $clasifIcon = match($clasif) {
                'CUMPLE'              => '✅',
                'CUMPLE_PARCIALMENTE' => '⚠️',
                'NO_CUMPLE'           => '❌',
                default               => '❓',
            };
        @endphp

        {{-- Resumen ejecutivo --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div style="font-size:2.5rem">{{ $clasifIcon }}</div>
                        <h5 class="font-weight-bold text-{{ $clasifColor }} mt-2">
                            {{ str_replace('_', ' ', $clasif ?? 'PENDIENTE') }}
                        </h5>
                        <div class="h2 font-weight-bold text-{{ $clasifColor }}">{{ $pct }}%</div>
                        <small class="text-muted">cumplimiento</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="font-weight-bold mb-3">Establecimiento</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><th class="text-muted small py-1">Nombre</th><td class="small">{{ $est->nombre_oficial }}</td></tr>
                            <tr><th class="text-muted small py-1">Tipología</th><td class="small">{{ $est->tipologia_clasificacion }}</td></tr>
                            <tr><th class="text-muted small py-1">Complejidad</th><td>
                                <span class="badge" style="background:{{ $est->complejidad_color }};color:#fff;font-size:.7rem">
                                    {{ $est->complejidad }}
                                </span>
                            </td></tr>
                            <tr><th class="text-muted small py-1">Nivel</th><td class="small">{{ $est->nivel_atencion }} / Grado {{ $est->grado_complejidad }}</td></tr>
                            <tr><th class="text-muted small py-1">Departamento</th><td class="small">{{ $est->departamento }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body" id="resumenGap">
                        <h6 class="font-weight-bold mb-3">Gap Analysis</h6>
                        <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-4" id="evalTabs" style="border-bottom:2px solid #dee2e6">
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabGap">
                    <i class="fa fa-chart-bar mr-1"></i>Cartera de Servicios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabHabilitacion">
                    <i class="fa fa-building mr-1"></i>Condiciones Habilitantes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabRespuestas">
                    <i class="fa fa-list mr-1"></i>Respuestas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" data-toggle="tab" href="#tabAcciones">
                    <i class="fa fa-exclamation-triangle mr-1 text-danger"></i>Acciones Críticas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active text-dark" data-toggle="tab" href="#tabMatriz">
                    <i class="fa fa-th mr-1"></i>Matriz de Servicios
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- Tab Gap Cartera --}}
            <div class="tab-pane fade" id="tabGap">
                <div id="gapContenido">
                    <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            {{-- Tab Habilitación --}}
            <div class="tab-pane fade" id="tabHabilitacion">
                <div id="habilitacionContenido">
                    <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            {{-- Tab Respuestas --}}
            <div class="tab-pane fade" id="tabRespuestas">
                @forelse($evaluacion->respuestas as $resp)
                <div class="d-flex align-items-start border-bottom py-2">
                    <div class="flex-grow-1">
                        <small class="text-muted">{{ $resp->pregunta->pregunta ?? '—' }}</small>
                        <div class="font-weight-bold">{{ $resp->respuesta }}</div>
                    </div>
                    <span class="badge badge-{{ $resp->estado_cumplimiento === 'cumple' ? 'success' : ($resp->estado_cumplimiento === 'no_cumple' ? 'danger' : 'secondary') }} ml-3">
                        {{ $resp->estado_cumplimiento }}
                    </span>
                </div>
                @empty
                <p class="text-muted text-center py-4">Sin respuestas registradas</p>
                @endforelse
            </div>

            {{-- Tab Acciones --}}
            <div class="tab-pane fade" id="tabAcciones">
                <div id="accionesContenido">
                    <div class="text-center py-5"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            {{-- Tab Matriz de Servicios --}}
            <div class="tab-pane fade show active" id="tabMatriz">
            @php
                use App\Models\Riiss\CarteraServicio;
                use App\Models\Riiss\ComplejidadTipo;

                // Mapeo dinámico: grado → columna aplica_* de CarteraServicio
                $mapGradoColumna = [
                    1 => 'aplica_puesto_sanitario',
                    2 => 'aplica_clinica_periferica',
                    3 => 'aplica_hospital_baja',
                    4 => 'aplica_hospital_mediana',
                    5 => 'aplica_hospital_alta',
                    6 => 'aplica_hospital_alta',
                ];

                $tiposComplejidad = ComplejidadTipo::activos()->get();

                $columnas = $tiposComplejidad->map(function($tipo) use ($mapGradoColumna) {
                    return [
                        'key'        => $mapGradoColumna[$tipo->grado] ?? null,
                        'label'      => $tipo->nombre,
                        'tipo_label' => $tipo->tipo_establecimiento,
                        'nivel'      => $tipo->nivel_atencion,
                        'grado'      => $tipo->grado,
                        'color'      => $tipo->color ?? '#1a237e',
                    ];
                })->filter(fn($c) => $c['key'] !== null)->values();

                $servicios = CarteraServicio::orderBy('tipo_prestacion')->orderBy('grupo_servicio')->orderBy('servicio')->get();
                $porTipo   = $servicios->groupBy('tipo_prestacion');

                // Columna del establecimiento evaluado — basada en complejidad_tipo_id
                $complejidadTipoEst = $evaluacion->establecimiento->complejidadTipo;
                $gradoEst   = $complejidadTipoEst?->grado ?? 0;
                $colActual  = $mapGradoColumna[$gradoEst] ?? null;

                // Gap analysis
                $gapItems = $evaluacion->gapAnalysis()->where('dimension','cartera_servicios')->get()->keyBy('servicio_nombre');

                $nombreNivel = $complejidadTipoEst?->nombre ?? 'Sin clasificación';
                $tipoLabel   = $complejidadTipoEst?->tipo_establecimiento ?? '';
            @endphp

            {{-- Encabezado del nivel --}}
            <div class="d-flex align-items-center mb-3 p-3 rounded"
                 style="background:linear-gradient(135deg,#1a237e,#283593);color:#fff">
                <div style="flex:1">
                    <div style="font-size:.65rem;opacity:.65;text-transform:uppercase;letter-spacing:.07em">Nivel evaluado</div>
                    <div style="font-size:1rem;font-weight:700">{{ $nombreNivel }}</div>
                    @if($tipoLabel)<div style="font-size:.75rem;opacity:.75">{{ $tipoLabel }}</div>@endif
                </div>
                <div class="text-right">
                    <div style="font-size:.65rem;opacity:.65;text-transform:uppercase">Total servicios</div>
                    <div style="font-size:1.5rem;font-weight:800">{{ $servicios->count() }}</div>
                </div>
            </div>

            {{-- Selector de columnas --}}
            <div class="mb-3 p-2 rounded d-flex flex-wrap align-items-center"
                 style="background:#f8faff;border:1px solid #e2e8f0;gap:.5rem">
                <span style="font-size:.72rem;font-weight:600;color:#475569;margin-right:.25rem">
                    <i class="fa fa-columns mr-1"></i>Columnas visibles:
                </span>
                @foreach($columnas->unique('key') as $col)
                @php $esActual = $col['key'] === $colActual; @endphp
                <label class="mb-0 d-flex align-items-center"
                       style="gap:.3rem;cursor:pointer;font-size:.75rem;
                              background:{{ $esActual ? '#e8eaf6' : '#fff' }};
                              border:1px solid {{ $esActual ? '#9fa8da' : '#e2e8f0' }};
                              border-radius:20px;padding:.2rem .6rem;
                              font-weight:{{ $esActual ? '600' : '400' }};
                              color:{{ $esActual ? '#1a237e' : '#64748b' }}">
                    <input type="checkbox"
                           class="col-toggle"
                           data-col="{{ $col['key'] }}"
                           {{ $esActual ? 'checked' : '' }}
                           style="cursor:pointer">
                    {{ $col['label'] }}
                    @if($esActual)<i class="fa fa-star ml-1" style="font-size:.6rem;color:#7986cb"></i>@endif
                </label>
                @endforeach
            </div>

            {{-- Tabla principal --}}
            <div class="table-responsive" style="max-height:62vh;overflow-y:auto;border:1px solid #e2e8f0;border-radius:.5rem">
            <table class="table table-bordered table-sm mb-0" id="matrizTable"
                   style="font-size:.78rem;border-collapse:collapse;color:#212529">
                <thead style="position:sticky;top:0;z-index:10">
                    <tr style="background:#1a237e;color:#fff;text-align:center">
                        <th rowspan="2" style="text-align:left;min-width:120px;vertical-align:middle;background:#1a237e;border-color:#283593">Tipo de Prestación</th>
                        <th rowspan="2" style="text-align:left;min-width:220px;vertical-align:middle;background:#1a237e;border-color:#283593">Servicio</th>
                        <th rowspan="2" style="text-align:center;width:90px;vertical-align:middle;background:#1565c0;border-color:#1565c0;font-size:.65rem">
                            Evaluación<br><span style="font-size:.55rem;opacity:.75;font-weight:400">en este estab.</span>
                        </th>
                        @foreach($columnas->unique('key') as $col)
                        <th class="col-header col-{{ $col['key'] }}"
                            style="background:#283593;border-color:#3949ab;min-width:110px;font-size:.68rem;
                                   {{ !($col['key'] === $colActual) ? 'display:none' : '' }}">
                            <div style="font-size:.6rem;opacity:.7;font-weight:400">{{ $col['tipo_label'] }}</div>
                            <div>{{ $col['label'] }}</div>
                            @if($col['key'] === $colActual)
                            <div style="font-size:.58rem;color:#90caf9;margin-top:.1rem">★ Este establecimiento</div>
                            @endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach($servicios->groupBy('tipo_prestacion') as $tipo => $items)
                    @foreach($items as $i => $srv)
                    @php
                        $gap = $gapItems->get($srv->servicio);
                        $rowBg = $gap
                            ? ($gap->estado === 'cumple'    ? '#f0fdf4'
                            : ($gap->estado === 'no_cumple' ? '#fef2f2' : ''))
                            : '';
                    @endphp
                    <tr style="{{ $rowBg ? 'background:'.$rowBg : '' }}">
                        @if($i === 0)
                        <td rowspan="{{ $items->count() }}"
                            style="font-weight:700;font-size:.72rem;color:#1a237e;vertical-align:middle;
                                   background:#e8eaf6;border-right:3px solid #9fa8da;white-space:nowrap">
                            {{ $tipo }}
                        </td>
                        @endif
                        <td style="vertical-align:middle">
                            {{ $srv->servicio }}
                            @if($srv->requerido)
                            <span style="font-size:.58rem;background:#e2e8f0;color:#475569;border-radius:3px;padding:1px 4px;margin-left:3px">req.</span>
                            @endif
                        </td>
                        {{-- Columna Evaluación separada --}}
                        <td style="text-align:center;vertical-align:middle;background:#f0f4ff">
                            @if($gap)
                                @if($gap->estado === 'cumple')
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:#dcfce7">
                                    <i class="fa fa-check" style="font-size:.65rem;color:#15803d"></i>
                                </span>
                                @elseif($gap->estado === 'no_cumple')
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:#fee2e2">
                                    <i class="fa fa-times" style="font-size:.65rem;color:#b91c1c"></i>
                                </span>
                                @else
                                <span style="color:#cbd5e1;font-size:.75rem">—</span>
                                @endif
                            @else
                            <span style="color:#cbd5e1;font-size:.75rem">—</span>
                            @endif
                        </td>
                        @foreach($columnas->unique('key') as $col)
                        <td class="col-cell col-{{ $col['key'] }}"
                            style="text-align:center;vertical-align:middle;
                                   {{ $col['key'] === $colActual ? 'background:#e3f2fd' : '' }};
                                   {{ !($col['key'] === $colActual) ? 'display:none' : '' }}">
                            @if($srv->{$col['key']})
                                <span style="display:inline-flex;align-items:center;justify-content:center;
                                             width:22px;height:22px;border-radius:50%;background:#dcfce7">
                                    <i class="fa fa-check" style="font-size:.62rem;color:#15803d"></i>
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;justify-content:center;
                                             width:22px;height:22px;border-radius:50%;background:#fee2e2">
                                    <i class="fa fa-times" style="font-size:.62rem;color:#b91c1c"></i>
                                </span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            </div>

            <div class="mt-2 px-1 d-flex flex-wrap" style="gap:.75rem;font-size:.72rem;color:#64748b">
                <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#dcfce7"><i class="fa fa-check" style="font-size:.55rem;color:#15803d"></i></span> Aplica al nivel</span>
                <span><span style="display:inline-flex;align-items:center;justify-content:center;width:16px;height:16px;border-radius:50%;background:#fee2e2"><i class="fa fa-times" style="font-size:.55rem;color:#b91c1c"></i></span> No aplica al nivel</span>
                <span style="background:#f0f4ff;padding:1px 6px;border-radius:3px;border:1px solid #c5cae9">Evaluación</span> = resultado en este establecimiento
                <span style="background:#e2e8f0;padding:1px 5px;border-radius:3px">req.</span> = requerido
                <span><i class="fa fa-star" style="color:#7986cb;font-size:.65rem"></i> = este establecimiento</span>
            </div>
            </div>

            <script>
            document.querySelectorAll('.col-toggle').forEach(function(cb) {
                cb.addEventListener('change', function() {
                    var col = this.dataset.col;
                    var show = this.checked;
                    document.querySelectorAll('.col-' + col).forEach(function(el) {
                        el.style.display = show ? '' : 'none';
                    });
                });
            });
            </script>

        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('riiss.evaluaciones.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left mr-1"></i>Volver
            </a>
            @if(in_array($evaluacion->estado, ['borrador','en_progreso']))
            <a href="{{ route('riiss.evaluaciones.nueva', $evaluacion->id_establecimiento) }}" class="btn btn-warning">
                <i class="fa fa-edit mr-1"></i>Continuar evaluación
            </a>
            @endif
            <button class="btn btn-danger" onclick="reejecutarGap()">
                <i class="fa fa-sync mr-1"></i>Re-ejecutar análisis
            </button>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
const EVAL_ID = {{ $evaluacion->id }};

$(document).ready(function() {
    cargarGap();
});

function cargarGap() {
    $.get(`/riiss/evaluaciones/${EVAL_ID}/gap`, function(r) {
        if (!r.ok) return;
        renderResumenGap(r.data);
        renderGapPorGrupo(r.data.cartera, 'gapContenido', 'cartera-');
        renderGapPorGrupo(r.data.habilitacion, 'habilitacionContenido', 'hab-');
        renderAcciones(r.data);
    });
}

function renderResumenGap(data) {
    const c = data.cartera;
    const h = data.habilitacion;
    const badgeFinal = {
        'APTO_HABILITACION': 'success',
        'OBSERVADO':         'warning',
        'NO_APTO':           'danger',
    }[data.clasificacion_final] ?? 'secondary';

    $('#resumenGap').html(`
        <h6 class="font-weight-bold mb-2">Resultado</h6>
        <div class="text-center mb-3">
            <span class="badge badge-${badgeFinal}" style="font-size:.85rem;padding:6px 12px">
                ${(data.clasificacion_final ?? '—').replace(/_/g,' ')}
            </span>
        </div>
        <div class="row text-center">
            <div class="col-6 mb-2 border-right">
                <div class="small text-muted font-weight-bold mb-1">Cartera Servicios</div>
                <div class="h4 font-weight-bold text-${c?.porcentaje >= 90 ? 'success' : c?.porcentaje >= 70 ? 'warning' : 'danger'}">${c?.porcentaje ?? 0}%</div>
                <small class="text-muted">✅ ${c?.resumen?.cumple ?? 0} / ❌ ${c?.resumen?.no_cumple ?? 0} / ⏳ ${c?.resumen?.pendiente ?? 0} / ⚠️ ${c?.resumen?.no_verificable ?? 0}</small>
            </div>
            <div class="col-6 mb-2">
                <div class="small text-muted font-weight-bold mb-1">Habilitación</div>
                <div class="h4 font-weight-bold text-${h?.porcentaje >= 90 ? 'success' : h?.porcentaje >= 70 ? 'warning' : 'danger'}">${h?.porcentaje ?? 0}%</div>
                <small class="text-muted">✅ ${h?.resumen?.cumple ?? 0} / ❌ ${h?.resumen?.no_cumple ?? 0}</small>
            </div>
        </div>
    `);
}

function renderGapPorGrupo(dim, containerId, prefix) {
    if (!dim?.por_grupo?.length) {
        $(`#${containerId}`).html('<p class="text-muted text-center py-4">Sin datos. Ejecute el análisis primero.</p>');
        return;
    }

    let html = '';
    dim.por_grupo.forEach((grupo, i) => {
        const key = prefix + i;
        const pct = grupo.total > 0 ? Math.round((grupo.cumple / grupo.total) * 100) : 0;
        const color = pct >= 90 ? '#22c55e' : pct >= 70 ? '#f97316' : '#ef4444';

        html += `<div class="mb-3">
            <div class="grupo-header d-flex align-items-center" onclick="toggleGrupo('${key}')">
                <strong class="flex-grow-1">${grupo.grupo ?? 'Sin grupo'}</strong>
                <div style="width:80px;margin-right:12px">
                    <div style="height:6px;background:#e5e7eb;border-radius:3px;overflow:hidden">
                        <div style="height:100%;width:${pct}%;background:${color};border-radius:3px"></div>
                    </div>
                    <small style="color:${color};font-weight:600">${pct}%</small>
                </div>
                <span class="badge badge-success mr-1">${grupo.cumple}</span>
                <span class="badge badge-danger mr-1">${grupo.no_cumple}</span>
                <span class="badge badge-warning">${grupo.no_verificable}</span>
                <i class="fa fa-chevron-down ml-2 text-muted" id="chevron-${key}"></i>
            </div>
            <div id="grupo-items-${key}" style="display:none;padding-left:12px">
                ${grupo.items.map(item => `
                    <div class="gap-item gap-${item.estado}">
                        <div class="d-flex align-items-start">
                            <span style="font-size:1.1rem;margin-right:8px">${item.icono}</span>
                            <div class="flex-grow-1">
                                <strong class="small">${item.servicio}</strong>
                                ${item.accion ? `<p class="text-muted small mb-0 mt-1">${item.accion}</p>` : ''}
                            </div>
                            <span class="badge badge-${item.prioridad >= 2 ? 'danger' : item.prioridad === 1 ? 'warning' : 'secondary'} ml-2">
                                ${item.prioridad >= 2 ? 'Crítico' : item.prioridad === 1 ? 'Alto' : 'Normal'}
                            </span>
                        </div>
                    </div>`).join('')}
            </div>
        </div>`;
    });

    $(`#${containerId}`).html(html);
}

function renderAcciones(data) {
    const criticos = [
        ...(data.cartera?.acciones_criticas ?? []),
        ...(data.habilitacion?.acciones_criticas ?? [])
    ];

    if (!criticos.length) {
        $('#accionesContenido').html('<p class="text-muted text-center py-4">No hay acciones críticas pendientes. ✅</p>');
        return;
    }

    let html = '<div class="alert alert-danger mb-3"><i class="fa fa-exclamation-triangle mr-2"></i>Se requieren acciones inmediatas:</div>';
    criticos.forEach(a => {
        html += `<div class="gap-item gap-no_cumple mb-3">
            <strong>${a.servicio}</strong>
            <span class="badge badge-secondary ml-2">${a.grupo ?? ''}</span>
            <p class="text-muted small mt-2 mb-0">${a.accion ?? ''}</p>
        </div>`;
    });

    $('#accionesContenido').html(html);
}

function toggleGrupo(key) {
    const $items = $(`#grupo-items-${key}`);
    const $chev  = $(`#chevron-${key}`);
    $items.slideToggle(200);
    $chev.toggleClass('fa-chevron-down fa-chevron-up');
}

function reejecutarGap() {
    Swal.fire({
        title: '¿Re-ejecutar análisis?',
        text: 'Esto sobreescribirá el análisis anterior.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e53e3e',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Sí, re-ejecutar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (!result.isConfirmed) return;
        $.ajax({
            url: `/riiss/evaluaciones/${EVAL_ID}/ejecutar-gap`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(r) {
                if (r.ok) { cargarGap(); mostrarToast('Análisis re-ejecutado', 'success'); }
            }
        });
    });
}

function mostrarToast(msg, tipo) {
    const color = tipo === 'success' ? '#22c55e' : '#ef4444';
    const toast = $(`<div style="position:fixed;bottom:24px;right:24px;background:${color};color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">${msg}</div>`);
    $('body').append(toast);
    setTimeout(() => toast.fadeOut(400, () => toast.remove()), 3000);
}
</script>
@endsection
