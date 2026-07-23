@extends('layouts.master')
@section('title', 'Proceso de Planificación')

@section('content')

@php
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;

$groupId     = $profile->group_id;
$descendantGroupIds = $profile->group
    ? $profile->group->descendants()->pluck('id')->push($groupId)->toArray()
    : [$groupId];

// ── Paso 2: FODA ──────────────────────────────────────────
$fodaPerfilVinculado = $profile->fodaPerfil;
$fodaPerfilId        = $fodaPerfilVinculado?->id;

if ($fodaPerfilId) {
    if (in_array($fodaPerfilVinculado->type, ['consolidado', 'grupal'])) {
        // Los análisis están en los perfiles de los subgrupos
        $subgroupIds  = \App\Admin\Globales\Group::where('parent_id', $fodaPerfilVinculado->group_id)->pluck('id');
        $subPerfilIds = \App\Admin\Planificacion\Foda\FodaPerfil::whereIn('group_id', $subgroupIds)->pluck('id');
        $fodaAnalisis = \App\Admin\Planificacion\Foda\FodaAnalisis::whereIn('perfil_id', $subPerfilIds)
            ->selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->pluck('total','tipo');
    } else {
        $fodaAnalisis = \App\Admin\Planificacion\Foda\FodaAnalisis::where('perfil_id', $fodaPerfilId)
            ->selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->pluck('total','tipo');
    }
} else {
    $fodaAnalisis = collect();
}
$fodaPendientes = $fodaAnalisis->get('Pendiente', 0);
$fodaAnalizados = $fodaAnalisis->except('Pendiente')->sum();

// ── Paso 3: Cruce de Ambientes ────────────────────────────
// Si el FODA vinculado es grupal/consolidado, el cruce está en foda-matriz-groups/{group_id}/crossing
// Si es individual, está en foda-cruce-ambientes/{perfil_id}
$fodaTipoGrupal = $fodaPerfilVinculado && in_array($fodaPerfilVinculado->type, ['grupal', 'consolidado']);
$groupIdCruce   = $fodaPerfilVinculado?->group_id;

$cruces = $fodaPerfilId
    ? FodaCruceAmbiente::where('perfil_id', $fodaPerfilId)
        ->selectRaw('tipo, COUNT(*) as total')->groupBy('tipo')->pluck('total','tipo')
    : collect();
$totalCruces = $cruces->sum();

// ── Paso 4: Identidad ─────────────────────────────────────
$tieneMision  = !empty(strip_tags($profile->mision ?? ''));
$tieneVision  = !empty(strip_tags($profile->vision ?? ''));
$tieneValores = !empty(strip_tags($profile->values ?? ''));

// ── Paso 5: Estrategias ───────────────────────────────────
// En el modelo MECIP las estrategias se vinculan al nivel 'axi' (Estrategia)
$totalEstrategias = $profile->descendants()->where('level', 'axi')
    ->whereHas('strategies')->count();
$axisConEstrategia = $totalEstrategias;
$totalEstrategiasFoda = $profile->descendants()->where('level', 'axi')
    ->withCount('strategies')->get()->sum('strategies_count');

// ── Paso 6: Estructura del Plan ───────────────────────────
$totalEjes      = $profile->descendants()->where('level','axi')->count();
$totalObjetivos = $profile->descendants()->where('level','goal')->count();
$totalAcciones  = $profile->descendants()->where('level','action')->count();

// ── Paso 7: Monitoreo ─────────────────────────────────────
$accionesConSemaforo = $profile->descendants()->where('level','action')
    ->whereNotNull('tipo_indicador')->count();
$rojos    = $profile->descendants()->where('level','action')->where('semaforo','rojo')->count();
$amarillos= $profile->descendants()->where('level','action')->where('semaforo','amarillo')->count();
$verdes   = $profile->descendants()->where('level','action')->where('semaforo','verde')->count();

// ── Estado de cada paso ───────────────────────────────────
function pasoEstado($condicion) {
    return $condicion ? 'completo' : 'pendiente';
}
$estados = [
    1 => pasoEstado($fodaPerfilId !== null),
    2 => pasoEstado($totalCruces > 0),
    3 => pasoEstado($tieneMision && $tieneVision),
    4 => pasoEstado($axisConEstrategia > 0),
    5 => pasoEstado($totalEjes > 0),
    6 => pasoEstado($accionesConSemaforo > 0),
];
$completados = collect($estados)->filter(fn($e) => $e === 'completo')->count();
$pctGlobal   = round(($completados / 6) * 100);
@endphp

<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Proceso de Planificación Estratégica</h4>
        <p class="card-category">{{ strip_tags($profile->name) }}</p>
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles PEI</a></li>
            <li class="breadcrumb-item active">{{ strip_tags($profile->name) }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Header ── --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-7">
                <h5 class="font-weight-bold mb-1">{{ strip_tags($profile->name) }}</h5>
                <small class="text-muted">
                    <i class="fa fa-calendar mr-1"></i>
                    {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }} –
                    {{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
                    @if($profile->group)
                        &nbsp;·&nbsp;<i class="fa fa-users mr-1"></i>{{ $profile->group->name }}
                    @endif
                </small>
            </div>
            <div class="col-md-5 text-right">
                <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn btn-sm btn-outline-info">
                    <i class="fa fa-edit mr-1"></i> Editar Plan
                </a>
                <a href="{{ route('pei-profiles.dashboard', $profile->id) }}" class="btn btn-sm btn-dark">
                    <i class="fa fa-chart-bar mr-1"></i> Tablero de Monitoreo
                </a>
                <a href="{{ route('pei-profiles.certificacion-mef', $profile->id) }}" class="btn btn-sm btn-warning">
                    <i class="fa fa-certificate mr-1"></i> Certificación MEF
                </a>
                <a href="{{ route('proyectos-institucionales.index', $profile->id) }}" class="btn btn-sm btn-success">
                    <i class="fa fa-project-diagram mr-1"></i> Proyectos
                </a>
            </div>
        </div>

        {{-- ── Progreso global ── --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-1">
                <small class="font-weight-bold text-muted text-uppercase" style="letter-spacing:.05em">
                    Progreso global del proceso
                </small>
                <small class="font-weight-bold">{{ $completados }}/6 pasos completados ({{ $pctGlobal }}%)</small>
            </div>
            <div class="progress" style="height:10px;border-radius:5px;">
                <div class="progress-bar {{ $pctGlobal == 100 ? 'bg-success' : 'bg-info' }}"
                     style="width:{{ $pctGlobal }}%;transition:width .6s ease"></div>
            </div>
        </div>

        {{-- ── Pasos ── --}}
        <div class="row">

            {{-- PASO 1: FODA --}}
            @include('admin.planificacion.peis.peis.partials.paso', [
                'num'    => 1,
                'titulo' => 'Diagnóstico FODA',
                'icono'  => 'fa-search',
                'estado' => $estados[1],
                'descripcion' => 'Análisis FODA grupal con validación IEA. Identifica fortalezas, debilidades, oportunidades y amenazas.',
                'metricas' => [
                    ['label' => 'Perfil FODA',  'valor' => $fodaPerfilVinculado ? $fodaPerfilVinculado->name : '⏳ Sin vincular'],
                    ['label' => 'Analizados',   'valor' => $fodaAnalizados],
                    ['label' => 'Pendientes',   'valor' => $fodaPendientes],
                    ['label' => 'Tipo',         'valor' => $fodaPerfilVinculado ? ucfirst($fodaPerfilVinculado->type) : '—'],
                ],
                'acciones' => array_filter([
                    $fodaPerfilId
                        ? ($fodaTipoGrupal
                            ? ['url' => route('foda-matriz-groups-crossing', $groupIdCruce), 'label' => 'Ver Perfil FODA', 'clase' => 'btn-info']
                            : ['url' => route('foda-cruce-ambientes', $fodaPerfilId), 'label' => 'Ver Perfil FODA', 'clase' => 'btn-info'])
                        : ['url' => route('pei-profiles.show', $profile->id), 'label' => 'Vincular FODA al PEI', 'clase' => 'btn-outline-info'],
                ]),
            ])

            {{-- PASO 2: Cruce de Ambientes --}}
            @include('admin.planificacion.peis.peis.partials.paso', [
                'num'    => 2,
                'titulo' => 'Cruce de Ambientes',
                'icono'  => 'fa-random',
                'estado' => $estados[2],
                'descripcion' => 'Definición de estrategias FO, FA, DO, DA a partir de la matriz FODA consolidada.',
                'metricas' => [
                    ['label' => 'Estrategias FO', 'valor' => $cruces->get('FO', 0)],
                    ['label' => 'Estrategias FA', 'valor' => $cruces->get('FA', 0)],
                    ['label' => 'Estrategias DO', 'valor' => $cruces->get('DO', 0)],
                    ['label' => 'Estrategias DA', 'valor' => $cruces->get('DA', 0)],
                ],
                'acciones' => $fodaPerfilId ? [
                    $fodaTipoGrupal
                        ? ['url' => route('foda-matriz-groups-crossing', $groupIdCruce), 'label' => 'Ver Cruce de Ambientes', 'clase' => 'btn-warning']
                        : ['url' => route('foda-cruce-ambientes', $fodaPerfilId), 'label' => 'Ver Cruce de Ambientes', 'clase' => 'btn-warning'],
                ] : [
                    ['url' => route('foda-list-groups'), 'label' => 'Ir a Matrices FODA', 'clase' => 'btn-outline-warning'],
                ],
            ])

            {{-- PASO 3: Identidad Institucional --}}
            @include('admin.planificacion.peis.peis.partials.paso', [
                'num'    => 3,
                'titulo' => 'Identidad Institucional',
                'icono'  => 'fa-landmark',
                'estado' => $estados[3],
                'descripcion' => 'Definición de Misión, Visión y Valores institucionales. Solo Alta Gerencia.',
                'metricas' => [
                    ['label' => 'Misión',  'valor' => $tieneMision  ? '✅ Definida'  : '⏳ Pendiente'],
                    ['label' => 'Visión',  'valor' => $tieneVision  ? '✅ Definida'  : '⏳ Pendiente'],
                    ['label' => 'Valores', 'valor' => $tieneValores ? '✅ Definidos' : '⏳ Pendiente'],
                ],
                'acciones' => [
                    ['url' => route('pei-profiles.show', $profile->id), 'label' => 'Definir Identidad', 'clase' => 'btn-success'],
                ],
            ])

            {{-- PASO 4: Objetivos / Nivel 1 --}}
            @include('admin.planificacion.peis.peis.partials.paso', [
                'num'    => 4,
                'titulo' => ($niveles['axi'] ?? 'Nivel 1') . 's del Plan',
                'icono'  => 'fa-bullseye',
                'estado' => $estados[4],
                'descripcion' => 'Definición de ' . ($niveles['axi'] ?? 'Nivel 1') . 's fundamentados en las estrategias del cruce FODA (FO/FA/DO/DA). Son los resultados generales que la institución pretende alcanzar.',
                'metricas' => [
                    ['label' => ($niveles['axi'] ?? 'Nivel 1') . 's con FODA', 'valor' => $axisConEstrategia],
                    ['label' => 'Total vínculos FODA',                          'valor' => $totalEstrategiasFoda],
                    ['label' => 'Total ' . ($niveles['axi'] ?? 'Nivel 1') . 's','valor' => $totalEjes],
                ],
                'acciones' => [
                    ['url' => route('pei-profiles.show', $profile->id), 'label' => 'Ir al Árbol del PEI', 'clase' => 'btn-success'],
                ],
            ])

            {{-- PASO 5: Estructura del Plan --}}
            @include('admin.planificacion.peis.peis.partials.paso', [
                'num'    => 5,
                'titulo' => 'Estructura del PEI (Marco Operativo)',
                'icono'  => 'fa-sitemap',
                'estado' => $estados[5],
                'descripcion' => 'Definición de ' . ($niveles['axi'] ?? 'Nivel 1') . ' → ' . ($niveles['goal'] ?? 'Nivel 2') . ' → ' . ($niveles['action'] ?? 'Acción') . ' con indicadores de gestión, responsables y presupuesto.',
                'metricas' => [
                    ['label' => ($niveles['axi']    ?? 'Nivel 1'), 'valor' => $totalEjes],
                    ['label' => ($niveles['goal']   ?? 'Nivel 2'), 'valor' => $totalObjetivos],
                    ['label' => ($niveles['action'] ?? 'Acción'),  'valor' => $totalAcciones],
                ],
                'acciones' => [
                    ['url' => route('pei-profiles.show', $profile->id), 'label' => 'Construir PEI', 'clase' => 'btn-primary'],
                    ['url' => route('pei-profiles.details', $profile->id), 'label' => 'Ver Árbol', 'clase' => 'btn-outline-primary'],
                ],
            ])

            {{-- PASO 6: Monitoreo --}}
            @include('admin.planificacion.peis.peis.partials.paso', [
                'num'    => 6,
                'titulo' => 'Monitoreo y Evaluación',
                'icono'  => 'fa-tachometer-alt',
                'estado' => $estados[6],
                'descripcion' => 'Seguimiento del avance por dirección con semáforo Lead/Lag y alertas presupuestarias.',
                'metricas' => [
                    ['label' => '🟢 Verde',     'valor' => $verdes],
                    ['label' => '🟡 Amarillo',  'valor' => $amarillos],
                    ['label' => '🔴 Rojo',      'valor' => $rojos],
                    ['label' => 'Con indicador', 'valor' => $accionesConSemaforo],
                ],
                'acciones' => [
                    ['url' => route('pei-profiles.dashboard', $profile->id), 'label' => 'Ver Tablero', 'clase' => 'btn-dark'],
                ],
            ])

        </div>{{-- row --}}
    </div>{{-- card-body --}}
</div>{{-- card --}}

@stop
