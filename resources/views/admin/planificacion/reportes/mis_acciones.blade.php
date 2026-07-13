@extends('layouts.master')
@section('title', 'Mis Acciones — ' . strip_tags($profile->name))

@section('content')
@php
$semaforoColor = ['verde'=>'#28a745','amarillo'=>'#ffc107','rojo'=>'#dc3545','sin-datos'=>'#adb5bd'];
$semaforoBg    = ['verde'=>'success','amarillo'=>'warning','rojo'=>'danger','sin-datos'=>'secondary'];
$baseUrl       = url('pei-profiles');
@endphp

{{-- ══ CABECERA ══════════════════════════════════════════════════════════════ --}}
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body py-3 px-4"
         style="background:linear-gradient(135deg,#1a237e 0%,#283593 100%);border-radius:.5rem">
        <div class="d-flex align-items-center flex-wrap" style="gap:.75rem">
            <div style="flex:1;min-width:0">
                <div class="d-flex align-items-center mb-1" style="gap:.5rem">
                    <span class="badge badge-light text-dark" style="font-size:.7rem">
                        {{ $niveles['master'] ?? 'PEI' }}
                    </span>
                    <span class="text-white-50" style="font-size:.72rem">
                        {{ \Carbon\Carbon::parse($profile->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($profile->year_end)->format('Y') }}
                    </span>
                </div>
                <h5 class="text-white mb-0 font-weight-bold" style="font-size:1rem;line-height:1.3">
                    {!! strip_tags($profile->name) !!}
                </h5>
            </div>
            <div class="text-right text-white-50" style="font-size:.75rem;flex-shrink:0">
                <i class="fa fa-user-circle fa-2x text-white mb-1 d-block"></i>
                {{ auth()->user()->name }}
            </div>
        </div>
    </div>
</div>

{{-- ══ RESUMEN RÁPIDO ════════════════════════════════════════════════════════ --}}
@php
    $totalMisAcciones = count($misAccionesIds);
    $reportadas = $ultimosReportes->count();
    $sinReporte  = $totalMisAcciones - $reportadas;
@endphp
<div class="row mb-3" style="gap:0">
    <div class="col-4 pr-1">
        <div class="card border-0 shadow-sm text-center py-2">
            <div style="font-size:1.6rem;font-weight:700;color:#1a237e">{{ $totalMisAcciones }}</div>
            <div style="font-size:.72rem;color:#666">Acciones asignadas</div>
        </div>
    </div>
    <div class="col-4 px-1">
        <div class="card border-0 shadow-sm text-center py-2">
            <div style="font-size:1.6rem;font-weight:700;color:#28a745">{{ $reportadas }}</div>
            <div style="font-size:.72rem;color:#666">Con reporte</div>
        </div>
    </div>
    <div class="col-4 pl-1">
        <div class="card border-0 shadow-sm text-center py-2">
            <div style="font-size:1.6rem;font-weight:700;color:{{ $sinReporte > 0 ? '#dc3545' : '#adb5bd' }}">{{ $sinReporte }}</div>
            <div style="font-size:.72rem;color:#666">Sin reporte</div>
        </div>
    </div>
</div>

{{-- ══ LISTA DE ACCIONES ═════════════════════════════════════════════════════ --}}
@if($totalMisAcciones === 0)
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="fa fa-inbox fa-3x mb-3 d-block" style="color:#dee2e6"></i>
        <p class="mb-1">No tenés acciones asignadas en este plan.</p>
        <small>Si creés que es un error, contactá al administrador del sistema.</small>
    </div>
</div>
@else

{{-- Recorrer árbol: axi > goal > action --}}
@foreach($profile->children->sortBy('order_item') as $axi)
    @php
        $accionesDelEje = $axi->descendants()->where('level','action')
            ->whereIn('id', $misAccionesIds)->get();
        if($accionesDelEje->isEmpty()) continue;
    @endphp

    {{-- Separador de Objetivo Estratégico --}}
    <div class="d-flex align-items-center mb-2 mt-3" style="gap:.5rem">
        <span style="width:4px;height:24px;background:#1a237e;border-radius:2px;flex-shrink:0"></span>
        <span style="font-size:.78rem;font-weight:700;color:#1a237e;text-transform:uppercase;letter-spacing:.04em">
            {{ $niveles['axi'] ?? 'Objetivo Estratégico' }}
        </span>
        <span style="font-size:.82rem;color:#333">{!! strip_tags($axi->name) !!}</span>
    </div>

    @foreach($accionesDelEje as $accion)
    @php
        $ultimoReporte = $ultimosReportes[$accion->id] ?? null;
        $semaforo      = $ultimoReporte->semaforo ?? ($accion->semaforo ?? 'sin-datos');
        $sc            = $semaforoColor[$semaforo] ?? '#adb5bd';
        $sb            = $semaforoBg[$semaforo]    ?? 'secondary';
        $ind           = $accion->indicador;
    @endphp

    <div class="card border-0 shadow-sm mb-2" id="accion-card-{{ $accion->id }}"
         style="border-left:4px solid {{ $sc }} !important;border-left-style:solid !important">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-start" style="gap:.75rem">

                {{-- Semáforo bullet --}}
                <div style="flex-shrink:0;margin-top:3px">
                    <span class="badge badge-{{ $sb }}"
                          style="width:14px;height:14px;border-radius:50%;display:inline-block;padding:0"
                          title="{{ ucfirst($semaforo) }}"></span>
                </div>

                {{-- Contenido --}}
                <div style="flex:1;min-width:0">
                    <div class="font-weight-bold" style="font-size:.85rem;color:#222;line-height:1.3">
                        {!! strip_tags($accion->name) !!}
                    </div>

                    @if($ind)
                    <div class="mt-1 d-flex align-items-center flex-wrap" style="gap:.3rem">
                        <span class="badge badge-light border" style="font-size:.68rem">
                            {{ $ind->codigo ?? '—' }}
                        </span>
                        <span style="font-size:.75rem;color:#555">{{ $ind->nombre }}</span>
                        @if($ind->unidad_medida)
                        <span class="text-muted" style="font-size:.7rem">· {{ $ind->unidad_medida }}</span>
                        @endif
                    </div>
                    @endif

                    @if($ultimoReporte)
                    <div class="mt-1 d-flex align-items-center flex-wrap" style="gap:.4rem">
                        <span style="font-size:.72rem;color:#888">
                            <i class="fa fa-clock mr-1"></i>Último reporte: {{ $ultimoReporte->fecha_reporte->format('d/m/Y') }}
                        </span>
                        @if($ultimoReporte->valor_numerador !== null)
                        <span class="badge badge-light border" style="font-size:.68rem">
                            Valor: {{ $ultimoReporte->valor_numerador }}
                        </span>
                        @endif
                        @if($ultimoReporte->pct_avance !== null)
                        <span class="badge badge-{{ $sb }}" style="font-size:.68rem">
                            {{ $ultimoReporte->pct_avance }}%
                        </span>
                        @endif
                    </div>
                    @if($ultimoReporte->descripcion_avance)
                    <div class="text-muted mt-1" style="font-size:.75rem;font-style:italic">
                        "{{ \Illuminate\Support\Str::limit($ultimoReporte->descripcion_avance, 100) }}"
                    </div>
                    @endif
                    @else
                    <div class="mt-1">
                        <span class="badge badge-secondary" style="font-size:.68rem">
                            <i class="fa fa-exclamation-circle mr-1"></i>Sin reporte aún
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Botón reportar --}}
                <div style="flex-shrink:0">
                    <button class="btn btn-sm btnReportar"
                            data-id="{{ $accion->id }}"
                            style="background:#1a237e;color:#fff;font-size:.75rem;padding:.3rem .7rem;border-radius:6px;white-space:nowrap">
                        <i class="fa fa-chart-line mr-1"></i>
                        {{ $ultimoReporte ? 'Actualizar' : 'Reportar' }}
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endforeach
@endforeach
@endif

{{-- ══ MODAL REPORTAR AVANCE ═════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalReportar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header py-2"
                 style="background:linear-gradient(135deg,#1a237e,#283593)">
                <div>
                    <h6 class="modal-title text-white mb-0">
                        <i class="fa fa-chart-line mr-2"></i>Reportar Avance
                    </h6>
                    <small class="text-white-50" id="rp_accion_nombre" style="font-size:.72rem"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body" style="padding:1.25rem 1.5rem">
                <input type="hidden" id="rp_accion_id">

                {{-- Ficha indicador --}}
                <div id="rp_ficha_ind" class="rounded mb-4" style="background:#eef2ff;display:none;border:1px solid #c5cae9;padding:.8rem 1rem">
                    <div class="d-flex align-items-center mb-2" style="gap:.4rem">
                        <span class="badge badge-primary" id="rp_ind_codigo" style="font-size:.68rem"></span>
                        <span id="rp_ind_dimension" class="badge" style="font-size:.68rem"></span>
                        <span id="rp_ind_sentido"></span>
                    </div>
                    <div class="font-weight-bold mb-1" id="rp_ind_nombre" style="color:#1a237e;font-size:.85rem"></div>
                    <div class="text-muted" style="font-size:.78rem">
                        <i class="fa fa-ruler-horizontal mr-1"></i>Unidad: <span id="rp_ind_unidad"></span>
                        &nbsp;·&nbsp;
                        Fórmula: <span id="rp_ind_formula"></span>
                    </div>
                    <div class="mt-1 font-weight-bold text-primary" id="rp_ind_meta" style="font-size:.8rem"></div>
                </div>

                {{-- Fecha y Período --}}
                <div class="form-row">
                    <div class="form-group col-6">
                        <label class="font-weight-600 d-block mb-2" style="font-size:.83rem;font-weight:600;color:#333">
                            Fecha del reporte <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="rp_fecha" class="form-control">
                    </div>
                    <div class="form-group col-6">
                        <label class="font-weight-600 d-block mb-2" style="font-size:.83rem;font-weight:600;color:#333">
                            Período
                        </label>
                        <input type="text" id="rp_periodo" class="form-control" placeholder="Ej: Ene–Mar 2025">
                    </div>
                </div>

                {{-- Valor logrado --}}
                <div class="form-group">
                    <label class="d-block mb-2" style="font-size:.83rem;font-weight:600;color:#333">
                        Valor logrado
                        <span id="rp_unidad_label" class="text-muted font-weight-normal ml-1" style="font-size:.78rem"></span>
                    </label>
                    <input type="number" step="any" id="rp_valor" class="form-control"
                           placeholder="Ingresá el valor numérico alcanzado">
                    <div id="rp_semaforo_prev" class="mt-2" style="display:none">
                        <span id="rp_semaforo_badge" class="badge px-3 py-1" style="font-size:.8rem"></span>
                        <small id="rp_pct_label" class="text-muted ml-2"></small>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="form-group">
                    <label class="d-block mb-2" style="font-size:.83rem;font-weight:600;color:#333">
                        Descripción del avance
                    </label>
                    <textarea id="rp_descripcion" class="form-control" rows="3"
                              placeholder="Describí brevemente los avances logrados…"></textarea>
                </div>

                {{-- Evidencia --}}
                <div class="form-row">
                    <div class="form-group col-8">
                        <label class="d-block mb-2" style="font-size:.83rem;font-weight:600;color:#333">URL de evidencia</label>
                        <input type="text" id="rp_evid_url" class="form-control" placeholder="https://…">
                    </div>
                    <div class="form-group col-4">
                        <label class="d-block mb-2" style="font-size:.83rem;font-weight:600;color:#333">Etiqueta</label>
                        <input type="text" id="rp_evid_label" class="form-control" placeholder="Informe PDF">
                    </div>
                </div>

                {{-- Historial --}}
                {{-- Historial --}}
                <div style="border-top:1px solid #eee;padding-top:.85rem;margin-top:.75rem">
                    <div class="d-flex align-items-center mb-2" style="gap:.5rem">
                        <i class="fa fa-history text-muted"></i>
                        <span style="font-size:.83rem;font-weight:600;color:#444">Reportes anteriores</span>
                        <span class="badge badge-secondary" id="rp_hist_count" style="font-size:.68rem">0</span>
                    </div>
                    <div id="rp_historial"
                         style="max-height:130px;overflow-y:auto;font-size:.78rem;background:#fafafa;border:1px solid #e9ecef;border-radius:6px;padding:.5rem .75rem">
                        <p class="text-muted text-center mb-0">Cargando…</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnGuardarReporte">
                    <i class="fa fa-save mr-1"></i>Guardar reporte
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Verificar que el modal existe en el DOM
    if ($('#modalReportar').length === 0) {
        console.error('SIPLAN: #modalReportar no encontrado en el DOM');
    }

    var _baseUrl   = '{{ $baseUrl }}';
    var _profileId = '{{ $profile->id }}';
    var _indicador = null;

    // ── Helpers ──────────────────────────────────────────────────────────────
    var dimColors  = { eficiencia:'#1976d2', eficacia:'#28a745', calidad:'#17a2b8', economia:'#ffc107' };
    var dimLabels  = { eficiencia:'Eficiencia', eficacia:'Eficacia', calidad:'Calidad', economia:'Economía' };
    var scColors   = { verde:'#28a745', amarillo:'#ffc107', rojo:'#dc3545' };
    var scFg       = { verde:'#fff', amarillo:'#000', rojo:'#fff' };

    function calcSemaforo(valor, ind) {
        if (!ind || !ind.metas) return null;
        var anio = new Date().getFullYear();
        var meta = ind.metas.find(function(m){ return m.anio == anio; });
        if (!meta) meta = ind.metas.slice().sort(function(a,b){return a.anio-b.anio;})
                            .find(function(m){ return m.anio >= anio; });
        if (!meta || !meta.valor) return null;
        var metaNum = parseFloat(meta.valor.toString().replace(/[^0-9.]/g,''));
        if (!metaNum) return null;
        var pct = Math.round((valor / metaNum) * 100 * 10) / 10;
        var sem;
        if (ind.sentido === 'descendente') {
            var ratio = valor / metaNum;
            sem = ratio <= 0.85 ? 'verde' : (ratio <= 1.0 ? 'amarillo' : 'rojo');
        } else {
            sem = pct >= 85 ? 'verde' : (pct >= 50 ? 'amarillo' : 'rojo');
        }
        return { semaforo: sem, pct: pct, metaLabel: meta.valor };
    }

    // ── Abrir modal ──────────────────────────────────────────────────────────
    $(document).on('click', '.btnReportar', function() {
        var id     = $(this).data('id');
        // Tomar el nombre del texto visible de la card
        var nombre = $(this).closest('.card-body').find('.font-weight-bold').first().text().trim();
        _indicador = null;

        $('#rp_accion_id').val(id);
        $('#rp_accion_nombre').text(nombre);
        $('#rp_fecha').val(new Date().toISOString().split('T')[0]);
        $('#rp_periodo,#rp_valor,#rp_descripcion,#rp_evid_url,#rp_evid_label').val('');
        $('#rp_ficha_ind,#rp_semaforo_prev').hide();
        $('#rp_historial').html('<p class="text-muted text-center mb-0">Cargando…</p>');
        $('#rp_hist_count').text('0');

        // Cargar indicador
        $.getJSON(_baseUrl + '/' + id + '/edit', function(data) {
            if (!data.profile.indicador_id) return;
            $.getJSON(_baseUrl + '/' + _profileId + '/indicadores', function(todos) {
                var ind = todos.find(function(i){ return i.id == data.profile.indicador_id; });
                if (!ind) return;
                _indicador = ind;
                var anio   = new Date().getFullYear();
                var meta   = ind.metas ? ind.metas.find(function(m){ return m.anio == anio; }) : null;
                $('#rp_ind_codigo').text(ind.codigo || '');
                $('#rp_ind_dimension').text(dimLabels[ind.dimension] || ind.dimension)
                    .attr('style','background:'+(dimColors[ind.dimension]||'#6c757d')+';color:#fff;font-size:.65rem');
                $('#rp_ind_sentido').html(ind.sentido === 'ascendente'
                    ? '<span class="text-success font-weight-bold">▲</span>'
                    : '<span class="text-danger font-weight-bold">▼</span>');
                $('#rp_ind_nombre').text(ind.nombre);
                $('#rp_ind_formula').text(ind.formula || '—');
                $('#rp_ind_unidad').text(ind.unidad_medida || '—');
                $('#rp_unidad_label').text(ind.unidad_medida ? '(' + ind.unidad_medida + ')' : '');
                $('#rp_ind_meta').text(meta ? 'Meta ' + anio + ': ' + meta.valor : 'Sin meta para ' + anio);
                $('#rp_ficha_ind').show();
            });
        });

        // Cargar historial
        $.getJSON(_baseUrl + '/' + id + '/reportes', function(reportes) {
            $('#rp_hist_count').text(reportes.length);
            var $h = $('#rp_historial').empty();
            if (!reportes.length) {
                $h.html('<p class="text-muted text-center mb-0" style="font-size:.75rem">Sin reportes previos.</p>');
                return;
            }
            reportes.forEach(function(r) {
                var sc = {verde:'success',amarillo:'warning',rojo:'danger','sin-datos':'secondary'}[r.semaforo]||'secondary';
                $h.append(
                    '<div class="d-flex align-items-start py-1" style="border-bottom:1px solid #f5f5f5;gap:.4rem">' +
                    '<span class="badge badge-'+sc+' flex-shrink-0" style="font-size:.62rem;margin-top:1px">'+(r.semaforo||'—')+'</span>'+
                    '<div style="flex:1;min-width:0">'+
                    '<strong style="font-size:.73rem">'+r.fecha_reporte+'</strong>'+(r.periodo_label?' — '+r.periodo_label:'')+
                    (r.valor_numerador!==null?' <span class="badge badge-light border" style="font-size:.65rem">'+r.valor_numerador+'</span>':'')+
                    (r.pct_avance!==null?' <small class="text-muted">('+r.pct_avance+'%)</small>':'')+
                    (r.descripcion_avance?'<div class="text-muted" style="font-size:.7rem">'+r.descripcion_avance+'</div>':'')+
                    '</div></div>'
                );
            });
        }).fail(function(){
            $('#rp_historial').html('<p class="text-muted text-center mb-0" style="font-size:.75rem">Sin reportes previos.</p>');
        });

        $('#modalReportar').modal('show');
    });

    // ── Preview semáforo en tiempo real ──────────────────────────────────────
    $('#rp_valor').on('input', function() {
        var val = parseFloat($(this).val());
        if (isNaN(val) || !_indicador) { $('#rp_semaforo_prev').hide(); return; }
        var res = calcSemaforo(val, _indicador);
        if (!res) { $('#rp_semaforo_prev').hide(); return; }
        $('#rp_semaforo_badge')
            .text(res.semaforo.toUpperCase() + ' — ' + res.pct + '%')
            .attr('style','background:'+(scColors[res.semaforo]||'#6c757d')+';color:'+(scFg[res.semaforo]||'#fff'));
        $('#rp_pct_label').text('Meta: ' + res.metaLabel);
        $('#rp_semaforo_prev').show();
    });

    // ── Guardar ──────────────────────────────────────────────────────────────
    $('#btnGuardarReporte').on('click', function() {
        var id    = $('#rp_accion_id').val();
        var fecha = $('#rp_fecha').val();
        if (!fecha) { toastr.warning('La fecha es obligatoria.'); return; }

        var $btn = $(this).prop('disabled', true).text('Guardando…');

        $.ajax({
            url:  _baseUrl + '/' + id + '/reportes',
            type: 'POST',
            data: {
                fecha_reporte:      fecha,
                periodo_label:      $('#rp_periodo').val(),
                valor_numerador:    $('#rp_valor').val() || null,
                descripcion_avance: $('#rp_descripcion').val(),
                evidencia_url:      $('#rp_evid_url').val(),
                evidencia_label:    $('#rp_evid_label').val(),
            },
            success: function(res) {
                toastr.success('Reporte guardado correctamente.');
                $('#modalReportar').modal('hide');
                // Actualizar borde de la card
                if (res.reporte && res.reporte.semaforo) {
                    var sc = {verde:'#28a745',amarillo:'#ffc107',rojo:'#dc3545','sin-datos':'#adb5bd'};
                    var col = sc[res.reporte.semaforo] || '#adb5bd';
                    $('#accion-card-' + id).css('border-left-color', col);
                    // Actualizar badge
                    $('#accion-card-' + id + ' .badge-secondary, #accion-card-' + id + ' .badge-danger, #accion-card-' + id + ' .badge-success, #accion-card-' + id + ' .badge-warning')
                        .first().text(res.reporte.semaforo).attr('class', 'badge badge-'+(res.reporte.semaforo==='verde'?'success':(res.reporte.semaforo==='amarillo'?'warning':(res.reporte.semaforo==='rojo'?'danger':'secondary'))));
                }
                setTimeout(function(){ location.reload(); }, 800);
            },
            error: function(xhr) {
                var resp = xhr.responseJSON || {};
                var e = resp.errors;
                if (e) {
                    $.each(e, function(k, v) { toastr.error(v[0]); });
                } else {
                    toastr.error(resp.message || 'Error al guardar.');
                }
                $btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i>Guardar reporte');
            }
        });
    });
});
</script>
@endsection
