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
            @hasanyrole('Administrador|Coordinador de Planificación')
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.index') }}">Perfiles PEI</a></li>
            @endhasanyrole
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
                @hasanyrole('Administrador|Coordinador de Planificación')
                <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn btn-sm btn-outline-info">
                    <i class="fa fa-edit mr-1"></i> Editar Plan
                </a>
                @endhasanyrole

                <button type="button" class="btn btn-sm btn-warning" id="btnProcesoCertMef">
                    <i class="fa fa-certificate mr-1"></i> Certificación MEF
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#modalConfigVariables">
                    <i class="fa fa-cogs mr-1"></i> Variables del Plan
                </button>
                @hasanyrole('Administrador|Coordinador de Planificación')
                <a href="{{ route('proyectos-institucionales.index', $profile->id) }}" class="btn btn-sm btn-success">
                    <i class="fa fa-project-diagram mr-1"></i> Proyectos
                </a>
                @endhasanyrole
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

                ],
            ])

        </div>{{-- row --}}
    </div>{{-- card-body --}}
</div>{{-- card --}}

{{-- ════════════════════════════════════════════════════════════════════════════
     MODAL: CERTIFICACIÓN MEF (CUMPLIMIENTO DE MATRICES)
     ════════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalCertificacionMef" tabindex="-1" role="dialog" aria-labelledby="modalCertificacionMefTitulo" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width: 900px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white d-flex align-items-center justify-content-between p-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalCertificacionMefTitulo">
                    <i class="fa fa-certificate text-warning mr-2"></i> Certificación MEF — {{ strip_tags($profile->name) }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalCertificacionMefBody" style="background-color: #f8fafc; min-height: 260px; max-height: 80vh; overflow-y: auto;">
                <div class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i>
                    <div>Cargando verificación de certificación MEF...</div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fa fa-info-circle text-info mr-1"></i> Verificación oficial de cumplimiento de estándares y matrices MEF.</small>
                <button type="button" class="btn btn-secondary btn-round px-4" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Variables del Plan --}}
<div class="modal fade" id="modalConfigVariables" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('pei-profiles.update-parameters', $profile->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-cogs mr-2"></i> Variables del Plan (Globales)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    @php
                        $params = is_array($profile->parameters) ? $profile->parameters : (json_decode($profile->parameters, true) ?? []);
                        $actaLogoUrl = $params['acta_logo_url'] ?? '';
                        $actaInstitucion = $params['acta_institucion'] ?? 'INSTITUTO DE PREVISIÓN SOCIAL';
                        $actaDependencia = $params['acta_dependencia'] ?? 'DIRECCIÓN DE PLANIFICACIÓN';
                    @endphp
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark"><i class="fa fa-building text-info mr-1"></i> Institución (Acta MECIP)</label>
                        <input type="text" name="acta_institucion" class="form-control p-2 border rounded shadow-sm" value="{{ $actaInstitucion }}" style="background-color: #f8f9fa;">
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark"><i class="fa fa-sitemap text-info mr-1"></i> Dependencia (Acta MECIP)</label>
                        <textarea name="acta_dependencia" class="form-control p-2 border rounded shadow-sm" rows="2" style="background-color: #f8f9fa;">{{ $actaDependencia }}</textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark"><i class="fa fa-image text-info mr-1"></i> URL del Logo (Acta MECIP)</label>
                        <input type="text" name="acta_logo_url" id="config_acta_logo_url" class="form-control p-2 border rounded shadow-sm" placeholder="Ej: https://midominio.com/logo.png" value="{{ $actaLogoUrl }}" style="background-color: #f8f9fa;">
                        <small class="text-muted d-block mt-2"><i class="fa fa-info-circle text-info"></i> Si se deja en blanco, usarán el logo predeterminado del sistema.</small>
                    </div>
                    
                    <div class="text-center bg-light p-3 border rounded shadow-sm" style="min-height: 110px;">
                        <small class="d-block text-muted mb-3 font-weight-bold text-uppercase">Vista Previa del Logo</small>
                        <img id="config_logo_preview" src="{{ !empty($actaLogoUrl) ? $actaLogoUrl : asset('material/img/new_logo.png') }}" style="max-height: 70px; max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-save mr-1"></i> Guardar Variables</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var inputLogo = document.getElementById('config_acta_logo_url');
        if (inputLogo) {
            inputLogo.addEventListener('input', function() {
                var url = this.value.trim();
                var preview = document.getElementById('config_logo_preview');
                preview.src = url ? url : '{{ asset('material/img/new_logo.png') }}';
            });
        }
    });
</script>

{{-- Modal Ficha Técnica de Indicador (reutilizable) --}}
@include('admin.planificacion.indicadores.modal_ficha', ['profile' => $profile])

@stop

@section('scripts')
<script>
    $(document).ready(function () {

        // Abrir collapses y hacer scroll si hay hash en la URL (para referencias del chat)
        if (window.location.hash) {
            const elementId = window.location.hash.substring(1);
            setTimeout(function() {
                function doScroll() {
                    const element = document.getElementById(elementId);
                    if (!element) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'warning', title: 'Elemento no encontrado', text: 'El elemento referenciado fue eliminado o modificado.', toast: true, position: 'top-end', timer: 3500, showConfirmButton: false });
                        }
                        return;
                    }
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    element.style.transition = 'all 0.4s ease';
                    element.style.boxShadow = '0 0 0 3px #22c55e, 0 0 20px rgba(34,197,94,0.5)';
                    element.style.borderRadius = '8px';
                    element.style.outline = '2px solid #16a34a';
                    setTimeout(function() { element.style.boxShadow = 'none'; element.style.outline = 'none'; }, 3500);
                }

                function expandAncestorsAndScroll(targetEl) {
                    const target = targetEl || document.getElementById(elementId);
                    if (!target) {
                        doScroll();
                        return;
                    }

                    const collapsesToOpen = [];
                    let current = target.parentElement;
                    while (current) {
                        if (current.classList && current.classList.contains('collapse') && !current.classList.contains('show')) {
                            collapsesToOpen.push(current);
                        }
                        current = current.parentElement;
                    }

                    const uniqueCollapses = collapsesToOpen.filter(function (collapseEl, index, array) {
                        return array.indexOf(collapseEl) === index;
                    }).reverse();

                    if (uniqueCollapses.length === 0) {
                        doScroll();
                        return;
                    }

                    let pending = uniqueCollapses.length;
                    uniqueCollapses.forEach(function (collapseEl) {
                        if ($(collapseEl).hasClass('show')) {
                            pending--;
                            if (pending === 0) setTimeout(doScroll, 50);
                            return;
                        }

                        $(collapseEl).one('shown.bs.collapse', function () {
                            pending--;
                            if (pending === 0) setTimeout(doScroll, 50);
                        });
                        $(collapseEl).collapse('show');
                    });
                }

                const targetElement = document.getElementById(elementId);
                if (!targetElement) {
                    setTimeout(function () {
                        expandAncestorsAndScroll();
                    }, 250);
                    return;
                }

                expandAncestorsAndScroll(targetElement);
            }, 600);
        }

        $('#btnProcesoCertMef').on('click', function() {
            var url = "{{ route('pei-profiles.certificacion-mef', $profile->id) }}";
            $('#modalCertificacionMefBody').html('<div class="text-center py-5 text-muted"><i class="fa fa-spinner fa-spin fa-2x mb-3 text-warning"></i><div>Cargando verificación de certificación MEF...</div></div>');
            $('#modalCertificacionMef').modal('show');

            $.get(url, function (html) {
                $('#modalCertificacionMefBody').html(html);
            }).fail(function (xhr) {
                var msg = xhr.responseJSON?.message || 'Ocurrió un error al cargar la certificación MEF.';
                $('#modalCertificacionMefBody').html('<div class="alert alert-danger mb-0"><i class="fa fa-exclamation-circle mr-2"></i> ' + msg + '</div>');
            });
        });
    });

    // Modal detalle indicador — usa el modal de ficha técnica reutilizable
    $(document).on('click', '.btn-ver-indicador', function() {
        const id      = $(this).data('id');
        const profile = $(this).data('profile');

        // Obtener todos los indicadores del perfil y encontrar el solicitado
        $.getJSON(`/pei-profiles/${profile}/indicadores`, function(data) {
            var ind = data.find(function(i) { return i.id == id; });
            if (!ind) { toastr.error('Indicador no encontrado.'); return; }

            // Resetear formulario
            $('#ind_id').val(ind.id);
            $('#ind_nombre').val(ind.nombre);
            $('#ind_codigo_letras').val(ind.codigo_letras);
            $('#ind_codigo_numeros').val(ind.codigo_numeros);

            // Radios
            $.each(['dimension','ambito','frecuencia','cobertura','sentido'], function(i, campo) {
                var val = ind[campo];
                var radio = $('input[name="ind_' + campo + '"][value="' + val + '"]');
                radio.prop('checked', true).trigger('change');
            });
            if (ind.frecuencia === 'otro') $('#ind_frecuencia_otro').val(ind.frecuencia_otro);

            // Campos de texto
            $('#ind_descripcion').val(ind.descripcion);
            $('#ind_variables').val(ind.variables);
            $('#ind_formula').val(ind.formula);
            $('#ind_unidad_medida').val(ind.unidad_medida);
            $('#ind_linea_base_anio').val(ind.linea_base_anio);
            $('#ind_linea_base_valor').val(ind.linea_base_valor);
            $('#ind_fuente').val(ind.fuente);
            $('#ind_dependencia_responsable').val(ind.dependencia_responsable);
            $('#ind_comentarios').val(ind.comentarios);

            // Metas
            $('#metasContainer').empty();
            if (ind.metas && ind.metas.length) {
                ind.metas.forEach(function(m) {
                    $('#metasContainer').append(
                        '<div class="col-md-3 mb-2 meta-row">' +
                        '<div class="input-group input-group-sm">' +
                            '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.72rem">Año</span></div>' +
                            '<input type="number" class="form-control meta-anio" value="' + m.anio + '" min="2020" max="2100">' +
                            '<input type="text" class="form-control meta-valor" value="' + m.valor + '">' +
                            '<div class="input-group-append"><button type="button" class="btn btn-outline-danger btn-remove-meta" style="font-size:.72rem"><i class="fa fa-times"></i></button></div>' +
                        '</div></div>'
                    );
                });
            }

            // Configurar modal en modo solo lectura
            $('#modalIndicadorTitulo').html('<i class="fa fa-eye mr-2"></i>Ficha del Indicador');
            $('#modalIndicadorSubtitulo').text(ind.nombre);
            $('#btnGuardarIndicador').hide();
            $('#modalIndicador').modal('show');
        }).fail(function() {
            toastr.error('Error al cargar la ficha del indicador.');
        });
    });

    // Mostrar botón guardar al cerrar el modal
    $('#modalIndicador').on('hidden.bs.modal', function() {
        $('#btnGuardarIndicador').show();
    });
</script>
@endsection
