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

                    {{-- Tipo --}}
                    <div class="form-group" id="tipoContainer">
                        <label class="font-weight-bold">Clasificación del Aspecto</label>
                        <div id="tipo" class="mt-2"></div>
                    </div>

                    {{-- IEA --}}
                    <div class="card bg-light mb-3">
                        <div class="card-body py-2">
                            <small class="text-muted text-uppercase font-weight-bold" style="font-size:.68rem;letter-spacing:.08em;">
                                <i class="fa fa-chart-bar mr-1 text-info"></i> Índice de Eficiencia de Activos (IEA)
                            </small>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <label style="font-size:.8rem;">Promedio Desempeño 6m</label>
                                    <input type="number" step="0.01" min="0" max="1" class="form-control form-control-sm" id="promedio_desempeno_6m" name="promedio_desempeno_6m" placeholder="0.00 – 1.00">
                                </div>
                                <div class="col-6">
                                    <label style="font-size:.8rem;">Inversión Histórica 6m</label>
                                    <input type="number" step="0.01" min="0.0001" class="form-control form-control-sm" id="inversion_historica_6m" name="inversion_historica_6m" placeholder="ej. 1.00">
                                </div>
                            </div>
                            <div id="ieaResultado" class="mt-2" style="display:none;">
                                <small>IEA calculado: <strong id="ieaValor"></strong>
                                <span id="ieaBadge" class="badge ml-1"></span></small>
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
                saveState: true,
                closedIcon: $('<i class="fas fa-arrow-circle-right text-info"></i>'),
                openedIcon: $('<i class="fas fa-arrow-circle-down text-info"></i>'),
                autoOpen: 1,
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
        if (d > 0 && i > 0) {
            var iea = (d / i).toFixed(4);
            var cls, label;
            if (iea < 0.4)      { cls = 'iea-debilidad'; label = 'Debilidad'; }
            else if (iea > 0.8) { cls = 'iea-fortaleza'; label = 'Fortaleza'; }
            else                { cls = 'iea-neutro';    label = 'Neutro'; }
            $('#ieaValor').text(iea);
            $('#ieaBadge').attr('class', 'badge ml-1 ' + cls).text(label);
            $('#ieaResultado').show();
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
        });
    });

    // ── Guardar análisis ─────────────────────────────────────
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
