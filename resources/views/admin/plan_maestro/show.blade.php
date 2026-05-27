@extends('layouts.master')
@section('title', $plan->nombre)

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">
            <i class="fa fa-shield-halved mr-2"></i>{{ $plan->nombre }}
        </h4>
        <p class="card-category">{{ $plan->descripcion }} — {{ $plan->institucion }}</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación</a></li>
            <li class="breadcrumb-item"><a href="{{ route('plan-maestro.index') }}">Plan Maestro</a></li>
            <li class="breadcrumb-item active">{{ $plan->nombre }}</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            @php
            $kpis = [
                ['label'=>'Total Acciones', 'valor'=>$stats['total'],     'color'=>'info',    'icon'=>'fa-list-check'],
                ['label'=>'Ejecutadas',     'valor'=>$stats['ejecutado'], 'color'=>'success', 'icon'=>'fa-check-circle'],
                ['label'=>'En Curso',       'valor'=>$stats['en_curso'],  'color'=>'warning', 'icon'=>'fa-spinner'],
                ['label'=>'Pendientes',     'valor'=>$stats['pendiente'], 'color'=>'danger',  'icon'=>'fa-clock'],
            ];
            @endphp
            @foreach($kpis as $k)
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-left-{{ $k['color'] }} shadow-sm h-100 py-2">
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="text-xs font-weight-bold text-{{ $k['color'] }} text-uppercase mb-1">{{ $k['label'] }}</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800" id="stat_{{ Str::slug($k['label']) }}">{{ $k['valor'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa {{ $k['icon'] }} fa-2x text-{{ $k['color'] }}" style="opacity:.3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Buscador y filtros ── --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body pb-2">
                <div class="row align-items-end">
                    <div class="col-md-5 mb-3">
                        <label class="font-weight-bold small text-uppercase text-muted">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </label>
                        <input type="text" id="pmSearch" class="form-control"
                               placeholder="Buscar acciones, diagnósticos, citas...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold small text-uppercase text-muted">Eje</label>
                        <select id="pmEje" class="form-control">
                            <option value="">Todos los ejes</option>
                            @foreach($ejes as $eje)
                            <option value="{{ $eje->codigo }}">{{ $eje->codigo }} — {{ $eje->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="font-weight-bold small text-uppercase text-muted">Momento</label>
                        <select id="pmMomento" class="form-control">
                            <option value="">Todos</option>
                            @foreach(\App\Models\PlanMaestro\PlanAccion::MOMENTOS as $key => $mom)
                            <option value="{{ $key }}">{{ $mom['short'] }} · {{ $mom['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="font-weight-bold small text-uppercase text-muted">Estado</label>
                        <select id="pmEstado" class="form-control">
                            <option value="">Todos</option>
                            <option value="EJECUTADO">Ejecutado</option>
                            <option value="EN CURSO">En Curso</option>
                            <option value="PENDIENTE">Pendiente</option>
                        </select>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="d-flex flex-wrap gap-1 mt-2 align-items-center justify-content-between" id="pmTabs">
                    <div class="d-flex flex-wrap gap-1">
                        <button class="btn btn-info btn-sm active-tab" data-tab="acciones">
                            <i class="fa fa-list-check mr-1"></i>Acciones
                            <span class="badge badge-light text-info ml-1">{{ $stats['total'] }}</span>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" data-tab="diagnostico">
                            <i class="fa fa-stethoscope mr-1"></i>Diagnóstico
                            <span class="badge badge-secondary ml-1">{{ $diagnosticos->count() }}</span>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" data-tab="canillas">
                            <i class="fa fa-faucet-drip mr-1"></i>Canillas de Fuga
                            <span class="badge badge-secondary ml-1">{{ $canillas->count() }}</span>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" data-tab="citas">
                            <i class="fa fa-quote-left mr-1"></i>Citas
                            <span class="badge badge-secondary ml-1">{{ $citas->count() }}</span>
                        </button>
                    </div>
                    <button class="btn btn-success btn-sm" id="btnNuevaAccion">
                        <i class="fa fa-plus mr-1"></i> Nueva Acción
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Contenido dinámico ── --}}
        <div id="pmContent">
            <div class="text-center py-5">
                <i class="fa fa-spinner fa-spin fa-2x text-info"></i>
            </div>
        </div>

    </div>
</div>

{{-- Modal nueva acción --}}
<div class="modal fade" id="modalNuevaAccion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2 bg-success text-white">
                <h6 class="modal-title font-weight-bold"><i class="fa fa-plus mr-2"></i>Nueva Acción</h6>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Eje <span class="text-danger">*</span></label>
                        <select id="naEje" class="form-control form-control-sm">
                            @foreach($ejes as $eje)
                            <option value="{{ $eje->id }}">{{ $eje->codigo }} — {{ $eje->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Momento <span class="text-danger">*</span></label>
                        <select id="naMomento" class="form-control form-control-sm">
                            @foreach(\App\Models\PlanMaestro\PlanAccion::MOMENTOS as $key => $mom)
                            <option value="{{ $key }}">{{ $mom['short'] }} · {{ $mom['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold">Estado inicial</label>
                        <select id="naEstado" class="form-control form-control-sm">
                            <option value="PENDIENTE">⏳ Pendiente</option>
                            <option value="EN CURSO">🔄 En Curso</option>
                            <option value="EN ELABORACIÓN">📝 En Elaboración</option>
                            <option value="EN DISEÑO">🎨 En Diseño</option>
                            <option value="COMPROMETIDO">🤝 Comprometido</option>
                            <option value="EJECUTADO">✅ Ejecutado</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Acción <span class="text-danger">*</span></label>
                    <textarea id="naAccion" class="form-control form-control-sm" rows="2"></textarea>
                    <small class="text-muted">Descripción concreta de la acción a ejecutar</small>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold">Justificación</label>
                    <textarea id="naJustificacion" class="form-control form-control-sm" rows="2"></textarea>
                    <small class="text-muted">¿Por qué es necesaria esta acción?</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">KPI / Indicador</label>
                        <input type="text" id="naKpi" class="form-control form-control-sm">
                        <small class="text-muted">Ej: % de cumplimiento al mes 3</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Plazo</label>
                        <input type="text" id="naPlazo" class="form-control form-control-sm">
                        <small class="text-muted">Ej: Días 30–60 / Mes 4</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Responsable</label>
                        <input type="text" id="naResponsable" class="form-control form-control-sm">
                        <small class="text-muted">Ej: Gerencia de Salud + Logística</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold">Detalle / Observaciones</label>
                        <input type="text" id="naDetalle" class="form-control form-control-sm">
                        <small class="text-muted">Información adicional</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-sm" id="btnGuardarAccion">
                    <i class="fa fa-save mr-1"></i>Guardar Acción
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal editar estado --}}
<div class="modal fade" id="modalEstado" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title font-weight-bold">Actualizar Estado</h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="estadoAccionId">
                <div class="form-group mb-2">
                    <label class="small font-weight-bold">Estado actual</label>
                    <input type="text" id="estadoActualTexto" class="form-control form-control-sm" readonly>
                </div>
                <div class="form-group mb-0">
                    <label class="small font-weight-bold">Nuevo estado</label>
                    <select id="estadoNuevo" class="form-control form-control-sm">
                        <option value="EJECUTADO">✅ Ejecutado</option>
                        <option value="EN CURSO">🔄 En Curso</option>
                        <option value="EN ELABORACIÓN">📝 En Elaboración</option>
                        <option value="EN DISEÑO">🎨 En Diseño</option>
                        <option value="EN ARMADO">🔧 En Armado</option>
                        <option value="COMPROMETIDO">🤝 Comprometido</option>
                        <option value="BAJO REVISIÓN">🔍 Bajo Revisión</option>
                        <option value="ORDENADA">📋 Ordenada</option>
                        <option value="IDENTIFICADA">🔎 Identificada</option>
                        <option value="ANUNCIADA">📢 Anunciada</option>
                        <option value="PENDIENTE">⏳ Pendiente</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-sm" id="btnGuardarEstado">
                    <i class="fa fa-save mr-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    var DIAGNOSTICOS = @json($diagnosticosJs);
    var CANILLAS     = @json($canillasJs);
    var CITAS        = @json($citasJs);
    var MOMENTOS     = @json(\App\Models\PlanMaestro\PlanAccion::MOMENTOS);
    var BUSCAR_URL   = '{{ route('plan-maestro.buscar', $plan) }}';
    var ESTADO_URL   = '{{ url('plan-maestro/acciones') }}';

    var state = { q: '', eje: '', momento: '', estado: '', tab: 'acciones' };
    var debounce;

    // Colores de estado
    var ESTADO_BADGE = {
        'EJECUTADO': 'badge-success',
        'EN CURSO':  'badge-warning',
        'PENDIENTE': 'badge-danger',
    };
    function estadoBadge(grupo, texto) {
        var cls = ESTADO_BADGE[grupo] || 'badge-secondary';
        return '<span class="badge ' + cls + ' badge-pill" style="font-size:.72rem">' + (texto||grupo) + '</span>';
    }

    function hl(text, q) {
        if (!q || q.length < 2 || !text) return text || '';
        var re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
        return String(text).replace(re, '<mark>$1</mark>');
    }
    function norm(s) {
        return String(s||'').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
    }
    function matchStatic(fields, q) {
        if (!q || q.length < 2) return true;
        return norm(fields.join(' ')).includes(norm(q));
    }

    // ── Acciones (AJAX) ──────────────────────────────────────────────────────
    function loadAcciones() {
        $('#pmContent').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-info"></i></div>');
        $.get(BUSCAR_URL, { q: state.q, eje: state.eje, momento: state.momento, estado_grupo: state.estado })
            .done(function(data) {
                updateStats(data);
                if (!data.acciones.length) {
                    $('#pmContent').html('<div class="alert alert-info"><i class="fa fa-search mr-2"></i>Sin resultados para los filtros aplicados.</div>');
                    return;
                }
                var html = '';
                data.acciones.forEach(function(a) {
                    var mom = MOMENTOS[a.momento] || { short: a.momento, label: a.momento, color: '#888' };
                    var borderColor = a.eje_color || '#17a2b8';
                    html += '<div class="card shadow-sm mb-2" style="border-left:4px solid ' + borderColor + '">';
                    html += '<div class="card-body py-2 px-3">';
                    html += '<div class="d-flex align-items-start justify-content-between">';
                    html += '<div class="flex-grow-1 mr-3">';
                    // Badges de cabecera
                    html += '<div class="mb-1">';
                    html += '<span class="badge badge-pill mr-1" style="background:' + borderColor + ';color:#fff;font-size:.7rem">' + a.eje_codigo + ' · ' + a.eje_nombre + '</span>';
                    html += '<span class="badge badge-pill badge-light border mr-1" style="font-size:.7rem;color:' + mom.color + '">' + mom.short + ' · ' + mom.label + '</span>';
                    html += estadoBadge(a.estado_grupo, a.estado);
                    html += '</div>';
                    // Título
                    html += '<p class="font-weight-bold mb-1" style="font-size:.9rem">' + hl(a.accion, state.q) + '</p>';
                    if (a.justificacion) html += '<p class="text-muted mb-1" style="font-size:.82rem">' + hl(a.justificacion, state.q) + '</p>';
                    if (a.detalle) html += '<p class="mb-1" style="font-size:.82rem">' + hl(a.detalle, state.q) + '</p>';
                    // Meta info
                    html += '<div class="d-flex flex-wrap gap-2 mt-1" style="font-size:.75rem;color:#6c757d">';
                    if (a.kpi) html += '<span><i class="fa fa-bullseye mr-1"></i>' + hl(a.kpi, state.q) + '</span>';
                    if (a.plazo) html += '<span class="ml-2"><i class="fa fa-clock mr-1"></i>' + hl(a.plazo, state.q) + '</span>';
                    if (a.responsable) html += '<span class="ml-2"><i class="fa fa-user-tie mr-1"></i>' + hl(a.responsable, state.q) + '</span>';
                    html += '</div>';
                    html += '</div>';
                    // Botón editar estado y eliminar
                    html += '<div class="flex-shrink-0 d-flex gap-1">';
                    html += '<button class="btn btn-circle btn-info btnEditarEstado" ';
                    html += 'data-id="' + a.id + '" data-estado="' + (a.estado||'') + '" ';
                    html += 'title="Actualizar estado">';
                    html += '<i class="fa fa-edit"></i></button>';
                    html += '<button class="btn btn-circle btn-danger btnEliminarAccion" ';
                    html += 'data-id="' + a.id + '" data-texto="' + (a.accion||'').substring(0,60) + '" ';
                    html += 'title="Eliminar acción">';
                    html += '<i class="fa fa-trash"></i></button>';
                    html += '</div>';
                    html += '</div></div></div>';
                });
                $('#pmContent').html(html);
            });
    }

    function updateStats(data) {
        var ej=0, ec=0, pe=0;
        (data.acciones||[]).forEach(function(a) {
            if (a.estado_grupo==='EJECUTADO') ej++;
            else if (a.estado_grupo==='EN CURSO') ec++;
            else pe++;
        });
        $('#stat_total-acciones').text(data.total||0);
        $('#stat_ejecutadas').text(ej);
        $('#stat_en-curso').text(ec);
        $('#stat_pendientes').text(pe);
    }

    // ── Diagnóstico ──────────────────────────────────────────────────────────
    function renderDiagnostico() {
        var q = state.q;
        var filtered = DIAGNOSTICOS.filter(function(d) { return matchStatic([d.titulo,d.contenido].concat(d.tags||[]),q); });
        if (!filtered.length) { $('#pmContent').html('<div class="alert alert-info">Sin resultados.</div>'); return; }
        var html = '';
        filtered.forEach(function(d) {
            html += '<div class="card shadow-sm mb-3"><div class="card-body">';
            html += '<h6 class="font-weight-bold text-info mb-2"><i class="fa fa-stethoscope mr-2"></i>' + hl(d.titulo,q) + '</h6>';
            html += '<p class="mb-2" style="font-size:.88rem;line-height:1.6">' + hl(d.contenido,q) + '</p>';
            if (d.tags && d.tags.length) {
                html += '<div>';
                d.tags.forEach(function(t) { html += '<span class="badge badge-light border mr-1 mb-1" style="font-size:.75rem">' + t + '</span>'; });
                html += '</div>';
            }
            html += '</div></div>';
        });
        $('#pmContent').html(html);
    }

    // ── Canillas ─────────────────────────────────────────────────────────────
    function renderCanillas() {
        var q = state.q;
        var filtered = CANILLAS.filter(function(c) { return matchStatic([c.tipo,c.ejemplos,c.estrategia,c.monto],q); });
        if (!filtered.length) { $('#pmContent').html('<div class="alert alert-info">Sin resultados.</div>'); return; }
        var html = '';
        filtered.forEach(function(c) {
            html += '<div class="card shadow-sm mb-3" style="border-left:4px solid #fd7e14">';
            html += '<div class="card-body">';
            html += '<h6 class="font-weight-bold mb-3" style="color:#b45309"><i class="fa fa-faucet-drip mr-2"></i>' + hl(c.tipo,q) + '</h6>';
            html += '<div class="row">';
            html += '<div class="col-md-4"><p class="text-xs font-weight-bold text-uppercase text-danger mb-1">Ejemplos detectados</p><p style="font-size:.85rem">' + hl(c.ejemplos,q) + '</p></div>';
            html += '<div class="col-md-4"><p class="text-xs font-weight-bold text-uppercase text-success mb-1">Estrategia de cierre</p><p style="font-size:.85rem">' + hl(c.estrategia,q) + '</p></div>';
            html += '<div class="col-md-4"><p class="text-xs font-weight-bold text-uppercase text-info mb-1">Monto / Impacto</p><p style="font-size:.85rem">' + hl(c.monto,q) + '</p></div>';
            html += '</div></div></div>';
        });
        $('#pmContent').html(html);
    }

    // ── Citas ─────────────────────────────────────────────────────────────────
    function renderCitas() {
        var q = state.q;
        var filtered = CITAS.filter(function(c) { return matchStatic([c.texto,c.autor,c.fecha,c.contexto],q); });
        if (!filtered.length) { $('#pmContent').html('<div class="alert alert-info">Sin resultados.</div>'); return; }
        var html = '';
        filtered.forEach(function(c) {
            html += '<div class="card shadow-sm mb-3"><div class="card-body">';
            html += '<blockquote class="blockquote mb-2">';
            html += '<p class="mb-2" style="font-size:.95rem;font-style:italic">"' + hl(c.texto,q) + '"</p>';
            html += '<footer class="blockquote-footer d-flex justify-content-between">';
            html += '<span class="font-weight-bold text-warning">' + hl(c.autor,q) + '</span>';
            html += '<span class="text-muted" style="font-size:.8rem">' + (c.fecha||'') + '</span>';
            html += '</footer></blockquote>';
            if (c.contexto) html += '<p class="text-muted mb-0" style="font-size:.8rem"><i class="fa fa-info-circle mr-1"></i>' + hl(c.contexto,q) + '</p>';
            html += '</div></div>';
        });
        $('#pmContent').html(html);
    }

    function render() {
        if (state.tab==='acciones')    loadAcciones();
        else if (state.tab==='diagnostico') renderDiagnostico();
        else if (state.tab==='canillas')    renderCanillas();
        else if (state.tab==='citas')       renderCitas();
    }

    // ── Eventos ──────────────────────────────────────────────────────────────
    $(function() {
        render();

        // Búsqueda
        $('#pmSearch').on('input', function() {
            clearTimeout(debounce);
            var v = $(this).val().trim();
            debounce = setTimeout(function() { state.q = v; render(); }, 280);
        });

        // Selects
        $('#pmEje').on('change', function() { state.eje = $(this).val(); render(); });
        $('#pmMomento').on('change', function() { state.momento = $(this).val(); render(); });
        $('#pmEstado').on('change', function() { state.estado = $(this).val(); render(); });

        // Tabs
        $(document).on('click', '#pmTabs [data-tab]', function(e) {
            e.preventDefault();
            state.tab = $(this).data('tab');
            $('#pmTabs [data-tab]').removeClass('btn-info').addClass('btn-outline-secondary');
            $(this).removeClass('btn-outline-secondary').addClass('btn-info');
            // Mostrar/ocultar filtros solo relevantes para acciones
            var soloAcciones = state.tab === 'acciones';
            $('#pmEje, #pmMomento, #pmEstado').closest('.col-md-3, .col-md-2').toggle(soloAcciones);
            render();
        });

        // Editar estado
        $(document).on('click', '.btnEditarEstado', function() {
            var id     = $(this).data('id');
            var estado = $(this).data('estado');
            $('#estadoAccionId').val(id);
            $('#estadoActualTexto').val(estado);
            $('#estadoNuevo').val(
                estado.startsWith('EJECUTADO') || estado.startsWith('CERRADO') ? 'EJECUTADO' :
                estado.startsWith('EN ') || estado.startsWith('COMPROMETIDO') || estado.startsWith('BAJO') ? 'EN CURSO' :
                estado
            );
            $('#modalEstado').modal('show');
        });

        $('#btnGuardarEstado').on('click', function() {
            var id     = $('#estadoAccionId').val();
            var estado = $('#estadoNuevo').val();
            var btn    = $(this);
            btn.html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...').prop('disabled', true);
            $.ajax({
                url:  ESTADO_URL + '/' + id + '/estado',
                type: 'PATCH',
                data: { estado: estado },
                success: function(r) {
                    $('#modalEstado').modal('hide');
                    toastr.success('Estado actualizado a: ' + r.estado);
                    render();
                },
                error: function() { toastr.error('Error al guardar.'); },
                complete: function() { btn.html('<i class="fa fa-save mr-1"></i>Guardar').prop('disabled', false); }
            });
        });

        // Nueva acción
        $('#btnNuevaAccion').on('click', function() {
            $('#naAccion, #naJustificacion, #naKpi, #naPlazo, #naResponsable, #naDetalle').val('');
            $('#naEstado').val('PENDIENTE');
            $('#modalNuevaAccion').modal('show');
        });

        $('#btnGuardarAccion').on('click', function() {
            var accion = $('#naAccion').val().trim();
            if (!accion) { toastr.warning('El campo Acción es obligatorio.'); $('#naAccion').focus(); return; }
            var btn = $(this);
            btn.html('<i class="fa fa-spinner fa-spin mr-1"></i>Guardando...').prop('disabled', true);
            $.post('{{ route('plan-maestro.accion.store', $plan) }}', {
                eje_id:        $('#naEje').val(),
                momento:       $('#naMomento').val(),
                estado:        $('#naEstado').val(),
                accion:        accion,
                justificacion: $('#naJustificacion').val(),
                kpi:           $('#naKpi').val(),
                plazo:         $('#naPlazo').val(),
                responsable:   $('#naResponsable').val(),
                detalle:       $('#naDetalle').val(),
            })
            .done(function() {
                $('#modalNuevaAccion').modal('hide');
                toastr.success('Acción creada correctamente.');
                state.tab = 'acciones';
                $('#pmTabs [data-tab]').removeClass('btn-info').addClass('btn-outline-secondary');
                $('#pmTabs [data-tab="acciones"]').removeClass('btn-outline-secondary').addClass('btn-info');
                render();
            })
            .fail(function(xhr) { toastr.error(xhr.responseJSON?.message || 'Error al guardar.'); })
            .always(function() { btn.html('<i class="fa fa-save mr-1"></i>Guardar Acción').prop('disabled', false); });
        });

        // Eliminar acción
        $(document).on('click', '.btnEliminarAccion', function() {
            var id    = $(this).data('id');
            var texto = $(this).data('texto');
            Swal.fire({
                title: '¿Eliminar acción?',
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Sí, eliminar'
            }).then(function(r) {
                if (!r.isConfirmed) return;
                $.ajax({
                    url:  '{{ url('plan-maestro/acciones') }}/' + id,
                    type: 'DELETE',
                })
                .done(function() { toastr.success('Acción eliminada.'); render(); })
                .fail(function() { toastr.error('Error al eliminar.'); });
            });
        });
        $(document).on('keydown', function(e) {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'SELECT') {
                e.preventDefault(); $('#pmSearch').focus();
            }
        });
    });
})();
</script>
@endsection
