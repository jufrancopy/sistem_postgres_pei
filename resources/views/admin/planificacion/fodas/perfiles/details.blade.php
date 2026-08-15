@extends('layouts.master')
@section('title', 'Detalle Perfil FODA')

@section('content')

@php
    $perfilId = $fodaProfile->id;
    $analisis = \App\Admin\Planificacion\Foda\FodaAnalisis::where('perfil_id', $perfilId)->get();
    $total      = $analisis->count();
    $pendientes = $analisis->where('tipo', 'Pendiente')->count();
    $fortalezas = $analisis->where('tipo', 'Fortaleza')->count();
    $debilidades= $analisis->where('tipo', 'Debilidad')->count();
    $oportunidades = $analisis->where('tipo', 'Oportunidad')->count();
    $amenazas   = $analisis->where('tipo', 'Amenaza')->count();
    $conIea     = $analisis->whereNotNull('iea_valor')->count();
@endphp

<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Detalle del Perfil FODA</h4>
        <p class="card-category">{{ $fodaProfile->name }}</p>
    </div>

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('foda-perfiles.index') }}">Perfiles FODA</a></li>
            <li class="breadcrumb-item active">{{ $fodaProfile->name }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── Header info + acciones rápidas ── --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h5 class="font-weight-bold mb-1">{{ $fodaProfile->name }}</h5>
                <small class="text-muted">
                    <i class="fa fa-tag mr-1"></i>{{ ucfirst($fodaProfile->type) }}
                    &nbsp;·&nbsp;
                    <i class="fa fa-book mr-1"></i>{{ $fodaProfile->model->name ?? '—' }}
                    @if($fodaProfile->group)
                        &nbsp;·&nbsp;
                        <i class="fa fa-users mr-1"></i>{{ $fodaProfile->group->name }}
                    @endif
                    @if($fodaProfile->dependency)
                        &nbsp;·&nbsp;
                        <i class="fa fa-building mr-1"></i>{{ $fodaProfile->dependency->dependency }}
                    @endif
                </small>
            </div>
            <div class="col-md-4 text-right">
                @if($fodaProfile->type === 'individual')
                    <a href="{{ route('foda-analisis-matriz', $perfilId) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-th mr-1"></i> Ver Matriz
                    </a>
                    <a href="{{ route('foda-cruce-ambientes', $perfilId) }}" class="btn btn-info btn-sm">
                        <i class="fa fa-random mr-1"></i> Cruce de Ambientes
                    </a>
                @else
                    <a href="{{ route('foda-list-groups') }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-layer-group mr-1"></i> Matriz Grupal
                    </a>
                @endif
                <a href="{{ route('foda-perfiles.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        {{-- ── KPI Cards ── --}}
        <div class="row mb-4">
            <div class="col-6 col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#17a2b8">{{ $total }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#6c757d">{{ $pendientes }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Pendientes</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#28a745">{{ $fortalezas }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Fortalezas</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#dc3545">{{ $debilidades }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Debilidades</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#28a745">{{ $oportunidades }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Oportunidades</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body py-3">
                        <div style="font-size:1.8rem;font-weight:700;color:#dc3545">{{ $amenazas }}</div>
                        <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.05em">Amenazas</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Barra de progreso ── --}}
        @if($total > 0)
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">Progreso del análisis</small>
                <small class="font-weight-bold">{{ round((($total - $pendientes) / $total) * 100) }}% completado</small>
            </div>
            <div class="progress" style="height:8px;border-radius:4px;">
                @php $pct = round((($total - $pendientes) / $total) * 100); @endphp
                <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
            </div>
            @if($conIea > 0)
            <small class="text-muted mt-1 d-block">
                <i class="fa fa-chart-bar mr-1 text-info"></i>
                {{ $conIea }} aspecto(s) con IEA calculado
            </small>
            @endif
        </div>
        @endif

        {{-- ── Participantes ── --}}
        @if($fodaProfile->group && $fodaProfile->group->members->count() > 0)
        <div class="mb-4">
            <p class="text-muted text-uppercase font-weight-bold mb-2" style="font-size:.72rem;letter-spacing:.08em;">
                <i class="fa fa-users mr-1 text-info"></i> Participantes del grupo
            </p>
            <div class="d-flex flex-wrap gap-1">
                @foreach($fodaProfile->group->members as $member)
                    <span class="badge badge-secondary mr-1 mb-1" style="font-size:.8rem;padding:.4em .7em;">
                        <i class="fa fa-user mr-1"></i>{{ $member->name }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Árbol de análisis ── --}}
        <p class="text-muted text-uppercase font-weight-bold mb-2" style="font-size:.72rem;letter-spacing:.08em;">
            <i class="fa fa-sitemap mr-1 text-info"></i> Árbol de Análisis FODA
        </p>

        <div class="card shadow-sm">
            <div class="card-body">
                <div id="treeProfile"></div>
                <div id="treeLoader" class="text-center py-4 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Cargando árbol...</p>
                </div>
            </div>
        </div>

    </div>{{-- card-body --}}
</div>{{-- card --}}

{{-- ── Modal Análisis ── --}}
<div class="modal fade" id="modalAnalysis" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-info">
                <h4 class="modal-title" id="headingAnalysis"></h4>
            </div>
            <div class="modal-body">
                <form id="analysisForm">
                    {{ Form::hidden('analysis_id', null, ['id' => 'analysis_id']) }}
                    {{ Form::hidden('perfil_id',   null, ['id' => 'perfil_id']) }}
                    {{ Form::hidden('aspecto_id',  null, ['id' => 'aspecto_id']) }}

                    {{-- ── Tarjeta Elegante: Referencia & Evidencia Documentada ── --}}
                    <div id="aspectoReferenciaContainer" class="card border-0 mb-3 shadow-xs" style="border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-left: 4px solid #0284c7 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="font-weight-bold text-uppercase" style="font-size:.72rem; letter-spacing:.05em; color: #1e293b;">
                                    <i class="fa fa-file-alt text-info mr-1.5"></i> Referencia & Evidencia Documentada
                                </span>
                                <span class="badge badge-light border text-info" style="font-size:.65rem; border-radius:10px;">
                                    <i class="fa fa-shield-alt mr-1"></i>Soporte IEA
                                </span>
                            </div>
                            <div id="aspectoReferenciaTexto" class="text-secondary small mt-1" style="font-size:.83rem; line-height: 1.45; color: #334155;">
                                <!-- Se carga dinámicamente -->
                            </div>
                        </div>
                    </div>

                    {{-- Tipo --}}
                    <div class="form-group" id="tipoContainer">
                        <label class="font-weight-bold">Clasificación del Aspecto</label>
                        <div id="tipo" class="mt-2"></div>
                    </div>

                    {{-- IEA --}}
                    <div class="card border-0 shadow-xs mb-3" style="border-radius: 12px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-uppercase font-weight-bold" style="font-size:.72rem; letter-spacing:.06em; color: #0f172a;">
                                    <i class="fa fa-chart-line mr-1 text-info"></i> ÍNDICE DE EFICIENCIA DE ACTIVOS (IEA)
                                </small>
                                <span class="text-muted" style="font-size:.68rem;" title="Fórmula: Promedio Desempeño / Inversión Histórica">
                                    <i class="fa fa-calculator mr-1"></i>IEA = Desempeño / Inversión
                                </span>
                            </div>
                            <div class="row mt-2.5">
                                <div class="col-6">
                                    <label class="font-weight-normal text-muted mb-1" style="font-size:.78rem;">Promedio Desempeño (6m)</label>
                                    <input type="number" step="0.01" min="0" max="1" class="form-control form-control-sm" id="promedio_desempeno_6m" name="promedio_desempeno_6m" placeholder="0.00 – 1.00" style="border-radius: 8px;">
                                </div>
                                <div class="col-6">
                                    <label class="font-weight-normal text-muted mb-1" style="font-size:.78rem;">Inversión Histórica (6m)</label>
                                    <input type="number" step="0.01" min="0.0001" class="form-control form-control-sm" id="inversion_historica_6m" name="inversion_historica_6m" placeholder="ej. 1.00" style="border-radius: 8px;">
                                </div>
                            </div>
                            <div id="ieaResultado" class="mt-2.5 pt-2 border-top" style="display:none;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <small class="text-muted" style="font-size:.75rem;">Resultado IEA:</small>
                                    <span id="ieaBadge" class="badge px-2.5 py-1" style="border-radius: 12px; font-size:.72rem;"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('ocurrencia', 'Nivel de Ocurrencia') }}
                        {{ Form::select('ocurrencia',
                            ['0.10'=>'Baja','0.30'=>'Media','0.50'=>'Alta','0.70'=>'Muy Alta','0.90'=>'Cierta'],
                            null, ['class'=>'form-control','placeholder'=>'','id'=>'ocurrencia','style'=>'width:100%']
                        ) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('impacto', 'Nivel de Impacto') }}
                        {{ Form::select('impacto',
                            ['0.05'=>'Muy Bajo','0.10'=>'Bajo','0.20'=>'Moderado','0.40'=>'Alto','0.80'=>'Muy Alto'],
                            null, ['class'=>'form-control','placeholder'=>'','id'=>'impacto','style'=>'width:100%']
                        ) }}
                    </div>

                    {{-- ── MECIP 2015: Gestión de Riesgo (solo Debilidad/Amenaza) ── --}}
                    <div id="mecipFields" style="display:none">

                        {{-- Separador con título ── --}}
                        <div class="d-flex align-items-center my-3">
                            <div style="flex:1;height:1px;background:#dee2e6"></div>
                            <span class="mx-2 px-2 py-1 rounded"
                                  style="background:#fff3cd;border:1px solid #ffc107;font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#856404;white-space:nowrap">
                                <i class="fa fa-shield-alt mr-1"></i> MECIP 2015 — Gestión de Riesgo
                            </span>
                            <div style="flex:1;height:1px;background:#dee2e6"></div>
                        </div>

                        {{-- Alerta contextual ── --}}
                        <div class="alert py-2 px-3 mb-3"
                             style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:6px">
                            <p class="mb-0" style="font-size:.78rem;color:#92400e;line-height:1.5">
                                <i class="fa fa-info-circle mr-1"></i>
                                Bajo MECIP 2015, esta debilidad/amenaza debe transformarse en un
                                <strong>riesgo institucional accionable</strong>.
                                Completá los campos para cerrar el ciclo:
                                <em>Identificación → Valoración → Respuesta → Seguimiento</em>.
                            </p>
                        </div>

                        {{-- Paso 1: Causa Raíz ── --}}
                        <div class="card mb-2 shadow-sm" style="border-radius:8px;border:1px solid #e9ecef">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                          style="width:22px;height:22px;background:#dc3545;color:#fff;font-size:.7rem;font-weight:700;flex-shrink:0">1</span>
                                    <span style="font-size:.82rem;font-weight:700;color:#343a40">Causa Raíz</span>
                                    <small class="text-muted ml-2">¿Por qué ocurre este problema?</small>
                                </div>
                                {{ Form::select('causa_raiz',
                                    \App\Admin\Planificacion\Foda\FodaAnalisis::CAUSAS_RAIZ,
                                    null,
                                    ['class'=>'form-control form-control-sm','placeholder'=>'Seleccionar tipo de causa...','id'=>'causa_raiz','style'=>'width:100%']
                                ) }}
                            </div>
                        </div>

                        {{-- Paso 2: Acción de Mejora ── --}}
                        <div class="card mb-2 shadow-sm" style="border-radius:8px;border:1px solid #e9ecef">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                          style="width:22px;height:22px;background:#fd7e14;color:#fff;font-size:.7rem;font-weight:700;flex-shrink:0">2</span>
                                    <span style="font-size:.82rem;font-weight:700;color:#343a40">Acción de Mejora</span>
                                    <span class="badge badge-warning ml-2" style="font-size:.62rem">Estrategia DO / DA</span>
                                </div>
                                <textarea name="accion_mejora" id="accion_mejora"
                                    class="form-control form-control-sm" rows="2"
                                    placeholder="Ej: Implementar tablero de control para automatizar el análisis de datos y reducir la carga manual..."></textarea>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted" style="font-size:.72rem">
                                        <i class="fa fa-arrow-right mr-1"></i>
                                        Respuesta concreta que cierra el ciclo de control interno
                                    </small>
                                    <button type="button" class="btn btn-outline-info btn-sm btnIaMecip py-0 px-2"
                                            data-campo="accion_mejora" style="font-size:.72rem" title="Generar con IA">
                                        <i class="fa fa-magic mr-1"></i> IA
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Paso 3: Control Preventivo ── --}}
                        <div class="card mb-3 shadow-sm" style="border-radius:8px;border:1px solid #e9ecef">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                                          style="width:22px;height:22px;background:#28a745;color:#fff;font-size:.7rem;font-weight:700;flex-shrink:0">3</span>
                                    <span style="font-size:.82rem;font-weight:700;color:#343a40">Control Preventivo</span>
                                    <small class="text-muted ml-2">Medida para evitar que el riesgo se materialice</small>
                                </div>
                                <textarea name="control_preventivo" id="control_preventivo"
                                    class="form-control form-control-sm" rows="2"
                                    placeholder="Ej: Revisión trimestral vinculada al POA con calendario de análisis definido en semanas críticas..."></textarea>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted" style="font-size:.72rem">
                                        <i class="fa fa-shield-alt mr-1"></i>
                                        Componente de Evaluación de Control — MECIP
                                    </small>
                                    <button type="button" class="btn btn-outline-info btn-sm btnIaMecip py-0 px-2"
                                            data-campo="control_preventivo" style="font-size:.72rem" title="Generar con IA">
                                        <i class="fa fa-magic mr-1"></i> IA
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveAnalysisBtn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<style>
    .jqtree-tree .jqtree-element { padding: 6px 8px; border-radius: 4px; }
    .jqtree-tree .jqtree-element:hover { background: #f8f9fa; }
    .jqtree-tree li.jqtree_common { border-bottom: 1px solid #f0f0f0; }
    .iea-fortaleza { background:#28a745; color:#fff; }
    .iea-debilidad { background:#dc3545; color:#fff; }
    .iea-neutro    { background:#6c757d; color:#fff; }

    /* Fix Select2 dentro de modal Bootstrap */
    .select2-container--open { z-index: 9999 !important; }
    .select2-dropdown        { z-index: 9999 !important; }
</style>

<script>
$(function () {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var perfilId = '{{ $perfilId }}';

    // ── Árbol ────────────────────────────────────────────────
    $.ajax({
        url: "{{ route('foda-perfiles.index') }}" + '/' + perfilId + '/get-tree',
        type: 'GET',
        success: function(data) {
            $('#treeLoader').hide();
            $('#treeProfile').tree({
                data: data,
                autoEscape: false,
                saveState: false,
                closedIcon: $('<i class="fas fa-plus-circle text-info mr-1"></i>'),
                openedIcon: $('<i class="fas fa-minus-circle text-info mr-1"></i>'),
                autoOpen: true,
                dragAndDrop: false
            });
        },
        error: function() {
            $('#treeLoader').html('<p class="text-danger"><i class="fa fa-exclamation-circle mr-1"></i>Error al cargar el árbol.</p>');
        }
    });

    // ── Calcular IEA en tiempo real ──────────────────────────
    $('#promedio_desempeno_6m, #inversion_historica_6m').on('input', function() {
        var d = parseFloat($('#promedio_desempeno_6m').val());
        var i = parseFloat($('#inversion_historica_6m').val());
        if (!isNaN(d) && !isNaN(i) && i > 0) {
            var iea = (d / i).toFixed(2);
            var cls, label;
            if (iea > 1.00) {
                cls = 'badge-success';
                label = 'IEA ' + iea + ' — Fortaleza (Alto Rendimiento)';
            } else if (Math.abs(iea - 1.00) <= 0.05) {
                cls = 'badge-warning text-dark';
                label = 'IEA ' + iea + ' — Zona de Equilibrio';
            } else {
                cls = 'badge-danger';
                label = 'IEA ' + iea + ' — Debilidad (Baja Eficiencia / Inversión)';
            }
            $('#ieaBadge').attr('class', 'badge px-2.5 py-1 ' + cls).html('<i class="fa fa-calculator mr-1"></i>' + label);
            $('#ieaResultado').slideDown(150);
        } else {
            $('#ieaResultado').hide();
        }
    });

    // ── Abrir modal de análisis ──────────────────────────────
    $('body').on('click', '.editAspect', function(e) {
        e.preventDefault();
        var aspectId   = $(this).data('id');
        var environment = $(this).data('environment');
        var nodeId     = $(this).data('node');

        $.get("{{ route('foda-analisis.index') }}" + '/' + aspectId + '/edit', function(data) {
            $('#modalAnalysis').modal('show');
            $('#analysis_id').val(data.id);
            $('#perfil_id').val(data.perfil_id);
            $('#aspecto_id').val(data.aspecto_id);
            $('#saveAnalysisBtn').attr('data-node', nodeId);
            $('#headingAnalysis').text('Analizar: ' + (data.model ? data.model.name : ''));

            // Cargar Referencia / Evidencia Documentada del Aspecto
            var refText = (data.model && data.model.description) ? data.model.description : (data.aspecto_referencia || '');
            if (refText && refText.trim() !== '') {
                $('#aspectoReferenciaTexto').html(refText);
            } else {
                $('#aspectoReferenciaTexto').html('<em class="text-muted"><i class="fa fa-info-circle mr-1 text-info"></i>Sin descripción documental cargada previamente. Se analizan los registros del periodo operativo.</em>');
            }

            // IEA
            $('#promedio_desempeno_6m').val(data.promedio_desempeno_6m || '');
            $('#inversion_historica_6m').val(data.inversion_historica_6m || '');
            $('#promedio_desempeno_6m').trigger('input');

            // Ocurrencia / Impacto
            $('#ocurrencia').select2({ placeholder: 'Seleccione' }).val(data.ocurrencia).trigger('change');
            $('#impacto').select2({ placeholder: 'Seleccione' }).val(data.impacto).trigger('change');

            // Tipo según ambiente
            var opciones = environment === 'Interno'
                ? [['Fortaleza','badge-success'],['Debilidad','badge-danger']]
                : [['Oportunidad','badge-success'],['Amenaza','badge-danger']];

            var $tipo = $('#tipo').empty();
            opciones.forEach(function(op) {
                $tipo.append(
                    $('<label class="mr-3">').append(
                        $('<input type="radio" name="tipo" class="mr-1">').val(op[0]).prop('checked', data.tipo === op[0]),
                        $('<span class="badge ' + op[1] + '">').text(op[0])
                    )
                );
            });

            // Mostrar campos MECIP si es Debilidad o Amenaza
            var esMecip = data.tipo === 'Debilidad' || data.tipo === 'Amenaza';
            $('#mecipFields').toggle(esMecip);

            // Cargar valores MECIP — el trigger('change') se hace en shown.bs.modal
            $('#causa_raiz').data('pendingVal', data.causa_raiz || '');
            $('#accion_mejora').val(data.accion_mejora || '');
            $('#control_preventivo').val(data.control_preventivo || '');
        });
    });

    // ── Mostrar campos MECIP según tipo seleccionado ─────────────────────────
    $(document).on('change', 'input[name="tipo"]', function() {
        var tipo = $(this).val();
        var esMecip = tipo === 'Debilidad' || tipo === 'Amenaza';
        $('#mecipFields').toggle(esMecip);
        if (!esMecip) {
            $('#causa_raiz').val('').trigger('change');
            $('#accion_mejora').val('');
            $('#control_preventivo').val('');
        }
    });

    // ── Generar MECIP con IA ──────────────────────────────────────────────────
    $(document).on('click', '.btnIaMecip', function() {
        var campo      = $(this).data('campo');
        var tipo       = $('input[name="tipo"]:checked').val();
        var causaRaiz  = $('#causa_raiz').val();
        var ocurrencia = $('select[name="ocurrencia"]').val() || $('#ocurrencia').val();
        var impacto    = $('select[name="impacto"]').val()    || $('#impacto').val();
        var aspectoNombre = $('#headingAnalysis').text().replace('Analizar: ', '').trim();

        if (!tipo || (tipo !== 'Debilidad' && tipo !== 'Amenaza')) {
            toastr.warning('Seleccioná primero el tipo (Debilidad o Amenaza).');
            return;
        }
        if (!causaRaiz) {
            toastr.warning('Seleccioná primero la Causa Raíz.');
            return;
        }
        if (!ocurrencia || !impacto) {
            toastr.warning('Seleccioná primero Ocurrencia e Impacto.');
            return;
        }

        var btn = $(this);
        btn.html('<i class="fa fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: '{{ route('foda-analisis.mecip-ia') }}',
            type: 'POST',
            data: {
                aspecto_nombre: aspectoNombre,
                tipo:           tipo,
                causa_raiz:     causaRaiz,
                ocurrencia:     ocurrencia,
                impacto:        impacto,
                perfil_id:      $('#perfil_id').val(),
            },
            success: function(resp) {
                if (campo === 'accion_mejora' && resp.accion_mejora) {
                    $('#accion_mejora').val(resp.accion_mejora);
                    toastr.success('Acción de mejora generada.');
                } else if (campo === 'control_preventivo' && resp.control_preventivo) {
                    $('#control_preventivo').val(resp.control_preventivo);
                    toastr.success('Control preventivo generado.');
                } else if (resp.error) {
                    toastr.error(resp.error);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || 'Error al conectar con la IA.');
            },
            complete: function() {
                btn.html('<i class="fa fa-magic mr-1"></i> IA').prop('disabled', false);
            }
        });
    });
    $('#modalAnalysis').on('shown.bs.modal', function() {
        if (!$('#causa_raiz').data('select2')) {
            $('#causa_raiz').select2({
                dropdownParent: $('#modalAnalysis'),
                placeholder: 'Seleccionar tipo de causa...',
                allowClear: true,
                width: '100%',
            });
        }
        // Aplicar valor pendiente si existe
        var pendingVal = $('#causa_raiz').data('pendingVal');
        if (pendingVal !== undefined) {
            $('#causa_raiz').val(pendingVal).trigger('change');
            $('#causa_raiz').removeData('pendingVal');
        }
    });

    // Evitar que el click en el dropdown de Select2 cierre el modal
    $(document).on('select2:open', function() {
        document.querySelector('.select2-search__field')?.focus();
    });

    // Prevenir que el modal se cierre al hacer click en el dropdown de Select2
    $('#modalAnalysis').on('mousedown', '.select2-container--open', function(e) {
        e.stopPropagation();
    });
    $('#saveAnalysisBtn').click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.html('Enviando...');

        var analysisId = $('#analysis_id').val();

        // Guardar análisis principal
        $.ajax({
            data: $('#analysisForm').serialize(),
            url: "{{ route('foda-analisis.store') }}",
            type: 'POST', dataType: 'json',
            success: function(data) {
                // Si hay datos IEA, calcularlos también
                var desempeno  = $('#promedio_desempeno_6m').val();
                var inversion  = $('#inversion_historica_6m').val();

                if (desempeno && inversion && analysisId) {
                    $.post("{{ url('foda-analisis') }}/" + analysisId + "/calcular-iea", {
                        promedio_desempeno_6m: desempeno,
                        inversion_historica_6m: inversion,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    });
                }

                toastr.success('Análisis guardado correctamente.');
                $('#analysisForm').trigger('reset');
                $('#modalAnalysis').modal('hide');
                $btn.html('Guardar');

                // Recargar árbol
                $.ajax({
                    url: "{{ route('foda-perfiles.index') }}" + '/' + perfilId + '/get-tree',
                    type: 'GET',
                    success: function(treeData) { $('#treeProfile').tree('loadData', treeData); }
                });
            },
            error: function(res) {
                $.each(res.responseJSON.errors || {}, function(k, v) {
                    toastr.error('Atención: ' + v);
                });
                $btn.html('Guardar');
            }
        });
    });
});
</script>
@stop
