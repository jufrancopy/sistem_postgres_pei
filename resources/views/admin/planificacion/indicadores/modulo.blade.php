@extends('layouts.master')
@section('title', 'Indicadores — ' . strip_tags($profile->name))

@section('content')
<div class="card">
    <div class="card-header card-header-info d-flex align-items-center">
        <div>
            <h4 class="card-title mb-0">
                <i class="fa fa-ruler-combined mr-2"></i>Fichas de Indicadores
            </h4>
            <small class="text-white" style="opacity:.8">{{ strip_tags($profile->name) }}</small>
        </div>
        <div class="ml-auto d-flex" style="gap:.5rem">
            <a href="{{ route('pei-profiles.show', $profile->id) }}" class="btn btn-sm btn-light">
                <i class="fa fa-arrow-left mr-1"></i> Volver al PEI
            </a>
            <button type="button" class="btn btn-sm btn-success" id="btnNuevoIndicador">
                <i class="fa fa-plus mr-1"></i> Nueva Ficha
            </button>
        </div>
    </div>

    <div class="card-body">

        @if($indicadores->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-ruler-combined fa-3x mb-3 d-block" style="opacity:.3"></i>
            <p class="mb-1">Sin indicadores registrados para este plan.</p>
            <small>Creá la primera ficha usando el botón <strong>Nueva Ficha</strong>.</small>
        </div>
        @else

        {{-- Panel de Filtros Interactivos y Agrupamiento con Select2 --}}
        <div class="p-3 mb-3 rounded border bg-light shadow-xs">
            <div class="row align-items-center" style="gap: .5rem 0;">
                {{-- Dimensión --}}
                <div class="col-md-3 col-6">
                    <label class="font-weight-bold text-dark mb-1" style="font-size:.72rem">
                        <i class="fa fa-layer-group text-info mr-1"></i>Dimensión
                    </label>
                    <select id="filterDimension" class="form-control form-control-sm select2-filter">
                        <option value="">Todas las Dimensiones</option>
                        @foreach(\App\Models\Planificacion\Indicador::DIMENSIONES as $key => $lbl)
                            <option value="{{ $key }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ámbito --}}
                <div class="col-md-3 col-6">
                    <label class="font-weight-bold text-dark mb-1" style="font-size:.72rem">
                        <i class="fa fa-sitemap text-warning mr-1"></i>Ámbito
                    </label>
                    <select id="filterAmbito" class="form-control form-control-sm select2-filter">
                        <option value="">Todos los Ámbitos</option>
                        @foreach(\App\Models\Planificacion\Indicador::AMBITOS as $key => $lbl)
                            <option value="{{ $key }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Frecuencia --}}
                <div class="col-md-3 col-6">
                    <label class="font-weight-bold text-dark mb-1" style="font-size:.72rem">
                        <i class="fa fa-calendar-alt text-secondary mr-1"></i>Frecuencia
                    </label>
                    <select id="filterFrecuencia" class="form-control form-control-sm select2-filter">
                        <option value="">Todas las Frecuencias</option>
                        @foreach(\App\Models\Planificacion\Indicador::FRECUENCIAS as $key => $lbl)
                            <option value="{{ $key }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Selector de Agrupamiento --}}
                <div class="col-md-3 col-6">
                    <label class="font-weight-bold text-primary mb-1" style="font-size:.72rem">
                        <i class="fa fa-object-group text-primary mr-1"></i>Agrupar Vista por
                    </label>
                    <select id="selectAgruparPor" class="form-control form-control-sm select2-filter">
                        <option value="none">Sin Agrupar (Tabla Plana)</option>
                        <option value="dimension">Agrupar por Dimensión</option>
                        <option value="ambito">Agrupar por Ámbito</option>
                        <option value="frecuencia">Agrupar por Frecuencia</option>
                        <option value="sentido">Agrupar por Sentido (▲/▼)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Resumen de Badges --}}
        @php
            $porDimension = $indicadores->groupBy('dimension');
            $colDim = ['eficiencia'=>'primary','eficacia'=>'success','calidad'=>'info','economia'=>'warning'];
        @endphp
        <div class="d-flex flex-wrap mb-3" style="gap:.5rem">
            @foreach($colDim as $dim => $color)
            @php $count = $porDimension->get($dim, collect())->count(); @endphp
            @if($count > 0)
            <span class="badge badge-{{ $color }}" style="font-size:.8rem;padding:.4em .8em">
                {{ \App\Models\Planificacion\Indicador::DIMENSIONES[$dim] }} ({{ $count }})
            </span>
            @endif
            @endforeach
            <span class="badge badge-dark ml-auto" style="font-size:.8rem;padding:.4em .8em" id="badgeTotalIndicadores">
                Total: {{ $indicadores->count() }}
            </span>
        </div>

        {{-- Contenedor de Vista Agrupada --}}
        <div id="contenedorAgrupado" class="mb-3" style="display:none;"></div>

        {{-- Tabla Estándar --}}
        <div id="contenedorTablaPlana" class="table-responsive">
            <table class="table table-hover table-sm" id="tablaIndicadores" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th style="width:90px">Código</th>
                        <th>Nombre del Indicador</th>
                        <th style="width:100px">Dimensión</th>
                        <th style="width:130px">Ámbito</th>
                        <th style="width:90px">Frecuencia</th>
                        <th style="width:70px" class="text-center">Sentido</th>
                        <th style="width:100px">Línea Base</th>
                        <th style="width:100px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($indicadores as $ind)
                    @php
                        $colDimBadge = ['eficiencia'=>'primary','eficacia'=>'success','calidad'=>'info','economia'=>'warning'];
                        $dimColor    = $colDimBadge[$ind->dimension] ?? 'secondary';
                    @endphp
                    <tr id="ind-row-{{ $ind->id }}" data-dimension="{{ $ind->dimension }}" data-ambito="{{ $ind->ambito }}" data-frecuencia="{{ $ind->frecuencia }}" data-sentido="{{ $ind->sentido }}">
                        <td>
                            <span class="badge badge-dark" style="font-size:.72rem;letter-spacing:.03em">
                                {{ $ind->codigoCompleto() }}
                            </span>
                        </td>
                        <td style="font-size:.88rem;font-weight:500">
                            <div>{{ $ind->nombre }}</div>
                            @if($ind->variables)
                            <div class="text-muted" style="font-size:.72rem;font-style:italic">
                                <i class="fa fa-calculator text-info mr-1"></i>Variables: {{ \Illuminate\Support\Str::limit($ind->variables, 90) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $dimColor }}" style="font-size:.68rem">
                                {{ \App\Models\Planificacion\Indicador::DIMENSIONES[$ind->dimension] ?? $ind->dimension }}
                            </span>
                        </td>
                        <td style="font-size:.78rem">
                            {{ \App\Models\Planificacion\Indicador::AMBITOS[$ind->ambito] ?? $ind->ambito }}
                        </td>
                        <td style="font-size:.78rem">
                            {{ \App\Models\Planificacion\Indicador::FRECUENCIAS[$ind->frecuencia] ?? $ind->frecuencia }}
                            @if($ind->frecuencia === 'otro' && $ind->frecuencia_otro)
                            <small class="text-muted">({{ $ind->frecuencia_otro }})</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($ind->sentido === 'ascendente')
                                <span class="text-success font-weight-bold" title="Ascendente — más es mejor">▲</span>
                            @else
                                <span class="text-danger font-weight-bold" title="Descendente — menos es mejor">▼</span>
                            @endif
                        </td>
                        <td style="font-size:.78rem">
                            @if($ind->linea_base_anio)
                            <span class="text-muted">{{ $ind->linea_base_anio }}:</span>
                            {{ $ind->linea_base_valor ?? '—' }}
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-info py-0 px-2 btnVerFicha"
                                    data-id="{{ $ind->id }}" title="Ver ficha completa">
                                <i class="fa fa-eye" style="font-size:.72rem"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary py-0 px-2 btnEditarIndicador"
                                    data-id="{{ $ind->id }}" title="Editar">
                                <i class="fa fa-edit" style="font-size:.72rem"></i>
                            </button>
                            @role('Administrador')
                            <button class="btn btn-sm btn-outline-danger py-0 px-2 btnEliminarIndicador"
                                    data-id="{{ $ind->id }}"
                                    data-nombre="{{ $ind->nombre }}" title="Eliminar">
                                <i class="fa fa-trash" style="font-size:.72rem"></i>
                            </button>
                            @endrole
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

{{-- Incluir modal de ficha técnica --}}
@include('admin.planificacion.indicadores.modal_ficha', ['profile' => $profile])
@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var _profileId       = '{{ $profile->id }}';
    var _indicadorEditId = null;
    var _metaIndex       = 0;
    var _indicadoresData = @json($indicadores->map(fn($i) => array_merge($i->toArray(), ['codigo' => $i->codigoCompleto()])));

    // ── DataTable ──
    var dt = $('#tablaIndicadores').DataTable({
        pageLength: 25,
        order: [[0, 'asc']],
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ indicadores',
            infoEmpty: 'Sin indicadores',
            zeroRecords: 'No se encontraron resultados',
            paginate: { previous: '‹', next: '›' },
        },
        columnDefs: [
            { targets: [2, 3, 4, 5, 6, 7], orderable: false },
            { targets: 7, searchable: false },
        ],
    });

    // ── Radio seleccionable ──
    $(document).on('change', '.ind-radio', function() {
        var name  = $(this).attr('name');
        $('input[name="' + name + '"]').each(function() {
            var card  = $(this).closest('.ind-radio-card');
            var color = card.data('color') || 'secondary';
            card.removeClass('ind-selected-' + color);
        });
        var card  = $(this).closest('.ind-radio-card');
        var color = card.data('color') || 'secondary';
        card.addClass('ind-selected-' + color);
    });

    // ── Metas dinámicas ──
    function agregarMeta(anio, valor) {
        var idx = _metaIndex++;
        $('#metasContainer').append(
            '<div class="col-12 mb-1.5 meta-row" data-idx="' + idx + '">' +
                '<div class="input-group input-group-sm">' +
                    '<div class="input-group-prepend"><span class="input-group-text bg-light text-muted font-weight-bold" style="font-size:.7rem">📅 Año</span></div>' +
                    '<input type="number" class="form-control meta-anio font-weight-bold" placeholder="{{ date("Y") }}" value="' + (anio||'') + '" min="2020" max="2100" style="max-width:85px">' +
                    '<input type="text" class="form-control meta-valor ml-1" placeholder="Ej: 2, 85%, 1500" value="' + (valor||'') + '">' +
                    '<div class="input-group-append">' +
                        '<button type="button" class="btn btn-outline-danger btn-remove-meta" title="Eliminar Meta"><i class="fa fa-times"></i></button>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );
    }

    $('#btnAgregarMeta').on('click', function() { agregarMeta('',''); });
    $(document).on('click', '.btn-remove-meta', function() { $(this).closest('.meta-row').remove(); });

    function leerMetas() {
        var metas = [];
        $('#metasContainer .meta-row').each(function() {
            var anio  = parseInt($(this).find('.meta-anio').val());
            var valor = $.trim($(this).find('.meta-valor').val());
            if (anio && valor) metas.push({ anio, valor });
        });
        return metas;
    }

    // ── Reset formulario ──
    function resetForm() {
        _indicadorEditId = null;
        $('#ind_id').val('');
        $('#ind_nombre,#ind_codigo_letras,#ind_codigo_numeros').val('');
        $('#ind_descripcion,#ind_variables,#ind_formula,#ind_unidad_medida').val('');
        $('#ind_frecuencia_otro,#ind_linea_base_anio,#ind_linea_base_valor').val('');
        $('#ind_fuente,#ind_dependencia_responsable,#ind_comentarios').val('');
        $('[name^="ind_"]').filter(':radio').prop('checked', false);
        $('.ind-radio-card').each(function() {
            var color = $(this).data('color') || 'secondary';
            $(this).removeClass('ind-selected-' + color);
        });
        $('#metasContainer').empty();
        _metaIndex = 0;
    }

    function cargarEnForm(ind) {
        resetForm();
        _indicadorEditId = ind.id;
        $('#ind_id').val(ind.id);
        $('#ind_nombre').val(ind.nombre);
        $('#ind_codigo_letras').val(ind.codigo_letras);
        $('#ind_codigo_numeros').val(ind.codigo_numeros);

        $.each(['dimension','ambito','frecuencia','cobertura','sentido'], function(i, c) {
            var rawVal = (ind[c] || '').toString().toLowerCase().trim();
            if (!rawVal) return;
            var $radio = $('input[name="ind_' + c + '"]').filter(function() {
                return $(this).val().toLowerCase() === rawVal;
            });
            if ($radio.length) {
                $radio.prop('checked', true).trigger('change');
            }
        });

        if (ind.frecuencia === 'otro') {
            $('#ind_frecuencia_otro').val(ind.frecuencia_otro);
            $('#container_frecuencia_otro').show();
        } else {
            $('#container_frecuencia_otro').hide();
        }

        $('#ind_descripcion').val(ind.descripcion);
        $('#ind_variables').val(ind.variables);
        $('#ind_formula').val(ind.formula);
        $('#ind_unidad_medida').val(ind.unidad_medida);
        $('#ind_linea_base_anio').val(ind.linea_base_anio);
        $('#ind_linea_base_valor').val(ind.linea_base_valor);
        $('#ind_fuente').val(ind.fuente);
        $('#ind_dependencia_responsable').val(ind.dependencia_responsable);
        $('#ind_comentarios').val(ind.comentarios);
        if (ind.metas && ind.metas.length) ind.metas.forEach(m => agregarMeta(m.anio, m.valor));
    }

    // ── Autocompletar código ──
    function autoCompletarCodigo() {
        $.getJSON('{{ url("pei-profiles") }}/' + _profileId + '/indicadores/siguiente-codigo',
            function(res) {
                $('#ind_codigo_letras').val(res.letras);
                $('#ind_codigo_numeros').val(res.numeros);
            }
        );
    }

    // ── Nuevo ──
    $('#btnNuevoIndicador').on('click', function() {
        resetForm();
        $('#modalIndicadorTitulo').html('<i class="fa fa-ruler-combined mr-2"></i>Nueva Ficha de Indicador');
        $('#modalIndicadorSubtitulo').text('');
        $('#modalIndicador').modal('show');
        autoCompletarCodigo();
    });

    // ── Editar ──
    $(document).on('click', '.btnEditarIndicador', function() {
        var id  = $(this).data('id');
        var ind = _indicadoresData.find(i => i.id == id);
        if (!ind) return;
        cargarEnForm(ind);
        $('#modalIndicadorTitulo').html('<i class="fa fa-edit mr-2"></i>Editar Ficha de Indicador');
        $('#modalIndicadorSubtitulo').text(ind.nombre);
        $('#modalIndicador').modal('show');
    });

    // ── Ver ficha (solo lectura en modal) ──
    $(document).on('click', '.btnVerFicha', function() {
        var id  = $(this).data('id');
        var ind = _indicadoresData.find(i => i.id == id);
        if (!ind) return;
        cargarEnForm(ind);
        $('#modalIndicadorTitulo').html('<i class="fa fa-eye mr-2"></i>Ficha del Indicador');
        $('#modalIndicadorSubtitulo').text(ind.nombre);
        $('#btnGuardarIndicador').hide();
        $('#modalIndicador').modal('show');
    });

    $('#modalIndicador').on('hidden.bs.modal', function() {
        $('#btnGuardarIndicador').show();
    });

    // ── Eliminar ──
    $(document).on('click', '.btnEliminarIndicador', function() {
        var id     = $(this).data('id');
        var nombre = $(this).data('nombre');
        Swal.fire({
            title: '¿Eliminar indicador?',
            html: '<strong>' + nombre + '</strong><br><small class="text-muted">Si está asignado a acciones, se desvinculará.</small>',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("pei-profiles") }}/' + _profileId + '/indicadores/' + id,
                    type: 'DELETE',
                    success: function() {
                        toastr.success('Indicador eliminado.');
                        $('#ind-row-' + id).fadeOut(400, function() { $(this).remove(); });
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Error al eliminar.');
                    }
                });
            }
        });
    });

    // ── Guardar ──
    $('#btnGuardarIndicador').on('click', function() {
        var nombre = $.trim($('#ind_nombre').val());
        var dim    = getRadioValue('ind_dimension');
        var amb    = getRadioValue('ind_ambito');
        var frec   = getRadioValue('ind_frecuencia');
        var cob    = getRadioValue('ind_cobertura');
        var sent   = getRadioValue('ind_sentido');

        if (!nombre) { toastr.warning('El nombre es obligatorio.'); return; }
        if (!dim)    { toastr.warning('Seleccioná la dimensión del indicador.'); return; }
        if (!amb)    { toastr.warning('Seleccioná el ámbito del indicador.'); return; }
        if (!frec)   { toastr.warning('Seleccioná la frecuencia de medición.'); return; }
        if (!cob)    { toastr.warning('Seleccioná la cobertura geográfica.'); return; }
        if (!sent)   { toastr.warning('Seleccioná el sentido del indicador.'); return; }

        var payload = {
            nombre, dimension: dim, ambito: amb,
            codigo_letras:    $.trim($('#ind_codigo_letras').val()),
            codigo_numeros:   $.trim($('#ind_codigo_numeros').val()),
            descripcion:      $.trim($('#ind_descripcion').val()),
            variables:        $.trim($('#ind_variables').val()),
            formula:          $.trim($('#ind_formula').val()),
            unidad_medida:    $.trim($('#ind_unidad_medida').val()),
            frecuencia:       frec,
            frecuencia_otro:  $.trim($('#ind_frecuencia_otro').val()),
            cobertura:        cob, sentido: sent,
            linea_base_anio:  $('#ind_linea_base_anio').val() || null,
            linea_base_valor: $.trim($('#ind_linea_base_valor').val()),
            fuente:           $.trim($('#ind_fuente').val()),
            dependencia_responsable: $.trim($('#ind_dependencia_responsable').val()),
            comentarios:      $.trim($('#ind_comentarios').val()),
        };
        leerMetas().forEach(function(m, i) {
            payload['metas[' + i + '][anio]']  = m.anio;
            payload['metas[' + i + '][valor]'] = m.valor;
        });

        var url    = _indicadorEditId
            ? '{{ url("pei-profiles") }}/' + _profileId + '/indicadores/' + _indicadorEditId
            : '{{ url("pei-profiles") }}/' + _profileId + '/indicadores';

        $.ajax({
            url, type: _indicadorEditId ? 'PUT' : 'POST', data: payload,
            success: function(res) {
                toastr.success(_indicadorEditId ? 'Indicador actualizado.' : 'Indicador creado.');
                $('#modalIndicador').modal('hide');
                setTimeout(() => location.reload(), 700);
            },
            error: function(xhr) {
                var e = xhr.responseJSON?.errors;
                if (e) $.each(e, (k,v) => toastr.error(v[0]));
                else toastr.error(xhr.responseJSON?.message || 'Error al guardar.');
            }
        });
    });

    // ── Lógica de Filtros y Agrupamiento Dinámico con DataTables ──
    var mapDimensiones  = @json(\App\Models\Planificacion\Indicador::DIMENSIONES);
    var mapAmbitos      = @json(\App\Models\Planificacion\Indicador::AMBITOS);
    var mapFrecuencias  = @json(\App\Models\Planificacion\Indicador::FRECUENCIAS);

    // Integrar filtro nativo de DataTables
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        if (settings.nTable.id !== 'tablaIndicadores') return true;

        var rowNode = dt ? $(dt.row(dataIndex).node()) : $('#tablaIndicadores tbody tr').eq(dataIndex);
        if (!rowNode || !rowNode.length) return true;

        var rowDim  = rowNode.data('dimension');
        var rowAmb  = rowNode.data('ambito');
        var rowFrec = rowNode.data('frecuencia');

        var selDim  = $('#filterDimension').val();
        var selAmb  = $('#filterAmbito').val();
        var selFrec = $('#filterFrecuencia').val();

        if (selDim && rowDim !== selDim) return false;
        if (selAmb && rowAmb !== selAmb) return false;
        if (selFrec && rowFrec !== selFrec) return false;

        return true;
    });

    function aplicarFiltrosYAgrupacion() {
        var dim  = $('#filterDimension').val();
        var amb  = $('#filterAmbito').val();
        var frec = $('#filterFrecuencia').val();
        var modo = $('#selectAgruparPor').val();

        // 1. Filtrar lista de objetos JS
        var filtrados = _indicadoresData.filter(function(ind) {
            if (dim && ind.dimension !== dim) return false;
            if (amb && ind.ambito !== amb) return false;
            if (frec && ind.frecuencia !== frec) return false;
            return true;
        });

        if (modo === 'none') {
            $('#contenedorAgrupado').hide().empty();
            $('#contenedorTablaPlana').show();

            // Redibujar DataTables para aplicar los 3 filtros Select2
            if (dt) dt.draw();
            var totalVisibles = dt ? dt.rows({ filter: 'applied' }).count() : filtrados.length;
            $('#badgeTotalIndicadores').text('Total: ' + totalVisibles);
            return;
        }

        $('#badgeTotalIndicadores').text('Total: ' + filtrados.length);

        // Si se eligió un modo de agrupamiento
        $('#contenedorTablaPlana').hide();
        $('#contenedorAgrupado').show().empty();

        if (filtrados.length === 0) {
            $('#contenedorAgrupado').html('<div class="alert alert-warning text-center py-4">No se encontraron indicadores con los filtros seleccionados.</div>');
            return;
        }

        // Agrupar filtrados por la clave elegida
        var grupos = {};
        filtrados.forEach(function(ind) {
            var keyVal = ind[modo] || 'Sin definir';
            var groupLabel = keyVal;

            if (modo === 'dimension') groupLabel = mapDimensiones[keyVal] || keyVal;
            else if (modo === 'ambito') groupLabel = mapAmbitos[keyVal] || keyVal;
            else if (modo === 'frecuencia') groupLabel = mapFrecuencias[keyVal] || keyVal;
            else if (modo === 'sentido') groupLabel = keyVal === 'ascendente' ? '▲ Ascendente (Más es mejor)' : '▼ Descendente (Menos es mejor)';

            if (!grupos[groupLabel]) grupos[groupLabel] = [];
            grupos[groupLabel].push(ind);
        });

        // Renderizar secciones agrupadas
        var groupHtml = '';
        $.each(grupos, function(gTitle, list) {
            groupHtml += '<div class="card mb-3 shadow-xs border-0 rounded-lg overflow-hidden">' +
                '<div class="card-header bg-dark text-white d-flex align-items-center py-2 px-3">' +
                    '<h6 class="mb-0 font-weight-bold" style="font-size:.85rem"><i class="fa fa-folder-open text-info mr-2"></i>' + gTitle + '</h6>' +
                    '<span class="badge badge-pill badge-info ml-auto" style="font-size:.72rem">' + list.length + ' indicador(es)</span>' +
                '</div>' +
                '<div class="card-body p-2 bg-light">' +
                    '<div class="row" style="gap: .75rem 0;">';

            list.forEach(function(ind) {
                var dimColors = { eficiencia:'primary', eficacia:'success', calidad:'info', economia:'warning' };
                var badgeColor = dimColors[ind.dimension] || 'secondary';
                var sentidoIcon = ind.sentido === 'ascendente' ? '<span class="text-success font-weight-bold">▲ Ascendente</span>' : '<span class="text-danger font-weight-bold">▼ Descendente</span>';

                groupHtml += '<div class="col-md-6 mb-2">' +
                    '<div class="p-3 bg-white rounded border shadow-xs h-100 d-flex flex-column justify-content-between">' +
                        '<div>' +
                            '<div class="d-flex align-items-center justify-content-between mb-2 flex-wrap" style="gap:.3rem">' +
                                '<span class="badge badge-dark font-weight-bold" style="font-size:.7rem">' + ind.codigo + '</span>' +
                                '<span class="badge badge-' + badgeColor + '" style="font-size:.65rem">' + (mapDimensiones[ind.dimension]||ind.dimension) + '</span>' +
                                '<small class="text-muted" style="font-size:.68rem">' + (mapAmbitos[ind.ambito]||ind.ambito) + '</small>' +
                                sentidoIcon +
                            '</div>' +
                            '<h6 class="font-weight-bold text-dark mb-1" style="font-size:.88rem">' + ind.nombre + '</h6>' +
                            (ind.variables ? '<div class="p-2 mb-2 rounded bg-light text-muted" style="font-size:.73rem;border-left:3px solid #17a2b8"><i class="fa fa-calculator text-info mr-1"></i><strong>Variables:</strong> ' + ind.variables + '</div>' : '') +
                            '<div class="row text-muted mb-2" style="font-size:.72rem">' +
                                '<div class="col-6"><strong>Fórmula:</strong> ' + (ind.formula||'—') + '</div>' +
                                '<div class="col-6"><strong>Unidad:</strong> ' + (ind.unidad_medida||'—') + '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="pt-2 border-top d-flex align-items-center justify-content-between" style="font-size:.72rem">' +
                            '<span class="text-muted">Frecuencia: ' + (mapFrecuencias[ind.frecuencia]||ind.frecuencia) + '</span>' +
                            '<div style="white-space:nowrap">' +
                                '<button class="btn btn-xs btn-outline-info py-0 px-2 mr-1 btnVerFicha" data-id="' + ind.id + '" title="Ver Ficha"><i class="fa fa-eye"></i></button>' +
                                '<button class="btn btn-xs btn-outline-primary py-0 px-2 mr-1 btnEditarIndicador" data-id="' + ind.id + '" title="Editar"><i class="fa fa-edit"></i></button>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';
            });

            groupHtml += '</div></div></div>';
        });

        $('#contenedorAgrupado').html(groupHtml);
    }

    // ── Inicializar Select2 en Filtros ──
    $('.select2-filter').select2({
        width: '100%',
        minimumResultsForSearch: 6
    }).on('change', function() {
        aplicarFiltrosYAgrupacion();
    });

    // Escuchar eventos de cambio en filtros
    $('#filterBuscador').on('keyup input', aplicarFiltrosYAgrupacion);
});
</script>

<style>
.select2-container--default .select2-selection--single {
    height: 31px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    background-color: #ffffff !important;
    padding: 2px 8px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 25px !important;
    font-size: 0.78rem !important;
    color: #334155 !important;
    font-weight: 500 !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 29px !important;
}
#selectAgruparPor + .select2-container--default .select2-selection--single {
    border-color: #a855f7 !important;
    background-color: #faf5ff !important;
}
#selectAgruparPor + .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #7e22ce !important;
    font-weight: 700 !important;
}
</style>
@include('admin.planificacion.peis.peis.partials.chat_drawer')
@stop
