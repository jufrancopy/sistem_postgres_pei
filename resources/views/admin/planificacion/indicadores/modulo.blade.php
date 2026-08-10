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

        {{-- Resumen --}}
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
            <span class="badge badge-dark ml-auto" style="font-size:.8rem;padding:.4em .8em">
                Total: {{ $indicadores->count() }}
            </span>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-hover table-sm">
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
                    <tr id="ind-row-{{ $ind->id }}">
                        <td>
                            <span class="badge badge-dark" style="font-size:.72rem;letter-spacing:.03em">
                                {{ $ind->codigoCompleto() }}
                            </span>
                        </td>
                        <td style="font-size:.88rem;font-weight:500">{{ $ind->nombre }}</td>
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
            '<div class="col-md-3 mb-2 meta-row" data-idx="' + idx + '">' +
            '<div class="input-group input-group-sm">' +
                '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.72rem">Año</span></div>' +
                '<input type="number" class="form-control meta-anio" placeholder="{{ date("Y") }}" value="' + (anio||'') + '" min="2020" max="2100">' +
                '<input type="text" class="form-control meta-valor" placeholder="Meta" value="' + (valor||'') + '">' +
                '<div class="input-group-append"><button type="button" class="btn btn-outline-danger btn-remove-meta" style="font-size:.72rem"><i class="fa fa-times"></i></button></div>' +
            '</div></div>'
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
            $('input[name="ind_' + c + '"][value="' + ind[c] + '"]').prop('checked', true).trigger('change');
        });
        if (ind.frecuencia === 'otro') $('#ind_frecuencia_otro').val(ind.frecuencia_otro);
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
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
        }).then(function(r) {
            if (!r.isConfirmed) return;
            $.ajax({
                url: '{{ url("pei-profiles") }}/' + _profileId + '/indicadores/' + id,
                type: 'DELETE',
                success: function() {
                    toastr.success('Indicador eliminado.');
                    $('#ind-row-' + id).fadeOut(300, function() { $(this).remove(); });
                    _indicadoresData = _indicadoresData.filter(i => i.id != id);
                }
            });
        });
    });

    // ── Guardar ──
    $('#btnGuardarIndicador').on('click', function() {
        var nombre = $.trim($('#ind_nombre').val());
        var dim    = $('input[name="ind_dimension"]:checked').val();
        var amb    = $('input[name="ind_ambito"]:checked').val();
        var frec   = $('input[name="ind_frecuencia"]:checked').val();
        var cob    = $('input[name="ind_cobertura"]:checked').val();
        var sent   = $('input[name="ind_sentido"]:checked').val();

        if (!nombre) { toastr.warning('El nombre es obligatorio.'); return; }
        if (!dim)    { toastr.warning('Seleccioná la dimensión.'); return; }
        if (!amb)    { toastr.warning('Seleccioná el ámbito.'); return; }
        if (!frec)   { toastr.warning('Seleccioná la frecuencia.'); return; }
        if (!cob)    { toastr.warning('Seleccioná la cobertura.'); return; }
        if (!sent)   { toastr.warning('Seleccioná el sentido.'); return; }

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
});
</script>
@include('admin.planificacion.peis.peis.partials.chat_drawer')
@stop
