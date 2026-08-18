{{-- ══ Modal Ficha Técnica de Indicador (partial reutilizable) ══ --}}
<div class="modal fade" id="modalIndicador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:1100px">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <div>
                    <h5 class="modal-title text-white mb-0" id="modalIndicadorTitulo" style="font-size:1rem">
                        <i class="fa fa-ruler-combined mr-2"></i>Ficha del Indicador
                    </h5>
                    <small class="text-white" style="opacity:.75;font-size:.75rem" id="modalIndicadorSubtitulo"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            {{-- Body scrolleable --}}
            <div class="modal-body p-0" style="max-height:80vh;overflow-y:auto">

                {{-- Banda superior: Nombre + Código --}}
                <div class="px-4 pt-3 pb-3" style="background:#f0f4ff;border-bottom:2px solid #c5cae9">
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-2 mb-md-0">
                            <label class="ind-label"><span class="ind-num">1</span> Nombre del Indicador <span class="text-danger">*</span></label>
                            <input type="text" id="ind_nombre" class="form-control form-control-sm"
                                   placeholder="Nombre completo del indicador">
                        </div>
                        <div class="col-md-4">
                            <label class="ind-label"><span class="ind-num">2</span> Código</label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="ind_codigo_letras" class="form-control text-center text-uppercase font-weight-bold"
                                       maxlength="10" placeholder="AAA">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text px-1">-</span>
                                </div>
                                <input type="text" id="ind_codigo_numeros" class="form-control text-center font-weight-bold"
                                       maxlength="10" placeholder="000">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="ind_id">
                <input type="hidden" id="ind_pei_profile_id">

                {{-- Layout de dos columnas --}}
                <div class="row no-gutters">

                    {{-- ── Columna izquierda ────────────────────────────────────── --}}
                    <div class="col-md-6 px-4 py-3" style="border-right:1px solid #dee2e6">

                        {{-- 3. Dimensión --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">3</span> Dimensión <span class="text-danger">*</span></label>
                            <div class="row no-gutters" style="gap:0">
                                @foreach(['eficiencia'=>['Eficiencia','primary'],'eficacia'=>['Eficacia','success'],'calidad'=>['Calidad','info'],'economia'=>['Economía','warning']] as $val => $cfg)
                                <div class="col-6 pr-1 pb-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="{{ $cfg[1] }}">
                                        <input type="radio" name="ind_dimension" value="{{ $val }}" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center justify-content-center rounded" style="padding:.45rem;font-size:.83rem;border:2px solid #dee2e6;text-align:center">
                                            {{ $cfg[0] }}
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 4. Ámbito --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">4</span> Ámbito <span class="text-danger">*</span></label>
                            <div class="row no-gutters">
                                @foreach(['objetivo_estrategico'=>'Obj. Estratégico','objetivo_especifico'=>'Obj. Específico','accion_estrategica'=>'Acc. Estratégica','accion_operativa'=>'Acc. Operativa'] as $val => $lbl)
                                <div class="col-6 pr-1 pb-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="dark">
                                        <input type="radio" name="ind_ambito" value="{{ $val }}" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center justify-content-center rounded" style="padding:.45rem;font-size:.8rem;border:2px solid #dee2e6;text-align:center">
                                            {{ $lbl }}
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 9. Frecuencia --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">9</span> Frecuencia <span class="text-danger">*</span></label>
                            <div class="row no-gutters">
                                @foreach(['mensual'=>'Mensual','trimestral'=>'Trimestral','semestral'=>'Semestral','anual'=>'Anual'] as $val => $lbl)
                                <div class="col-6 pr-1 pb-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="secondary">
                                        <input type="radio" name="ind_frecuencia" value="{{ $val }}" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center justify-content-center rounded" style="padding:.4rem;font-size:.82rem;border:2px solid #dee2e6;text-align:center">
                                            {{ $lbl }}
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                                <div class="col-12 pr-1 pb-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="secondary">
                                        <input type="radio" name="ind_frecuencia" value="otro" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center rounded" style="padding:.4rem;font-size:.82rem;border:2px solid #dee2e6;gap:.4rem">
                                            <span style="white-space:nowrap">Otro:</span>
                                            <input type="text" id="ind_frecuencia_otro" class="form-control form-control-sm" placeholder="Especificar" style="flex:1">
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- 10. Cobertura --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">10</span> Cobertura Geográfica <span class="text-danger">*</span></label>
                            <div class="row no-gutters">
                                @foreach(['nacional'=>'Nacional','regional'=>'Regional','departamental'=>'Departamental','municipal'=>'Municipal'] as $val => $lbl)
                                <div class="col-6 pr-1 pb-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="teal">
                                        <input type="radio" name="ind_cobertura" value="{{ $val }}" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center justify-content-center rounded" style="padding:.4rem;font-size:.82rem;border:2px solid #dee2e6;text-align:center">
                                            {{ $lbl }}
                                        </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 11. Sentido --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">11</span> Sentido del Indicador <span class="text-danger">*</span></label>
                            <div class="row no-gutters">
                                <div class="col-6 pr-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="success">
                                        <input type="radio" name="ind_sentido" value="ascendente" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center justify-content-center rounded" style="padding:.5rem;font-size:.85rem;border:2px solid #dee2e6;gap:.3rem">
                                            <span class="text-success font-weight-bold">▲</span> Ascendente
                                        </span>
                                    </label>
                                    <small class="text-muted d-block text-center mt-1" style="font-size:.68rem">más es mejor</small>
                                </div>
                                <div class="col-6 pr-1">
                                    <label class="ind-radio-card w-100 mb-0" data-color="danger">
                                        <input type="radio" name="ind_sentido" value="descendente" class="ind-radio d-none">
                                        <span class="ind-card-inner d-flex align-items-center justify-content-center rounded" style="padding:.5rem;font-size:.85rem;border:2px solid #dee2e6;gap:.3rem">
                                            <span class="text-danger font-weight-bold">▼</span> Descendente
                                        </span>
                                    </label>
                                    <small class="text-muted d-block text-center mt-1" style="font-size:.68rem">menos es mejor</small>
                                </div>
                            </div>
                        </div>

                        {{-- 12. Línea de base --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">12</span> Línea de Base</label>
                            <div class="row">
                                <div class="col-5">
                                    <label class="ind-sublabel">Año</label>
                                    <input type="number" id="ind_linea_base_anio" class="form-control form-control-sm"
                                           placeholder="{{ date('Y') }}" min="2000" max="2100">
                                </div>
                                <div class="col-7">
                                    <label class="ind-sublabel">Valor</label>
                                    <input type="text" id="ind_linea_base_valor" class="form-control form-control-sm"
                                           placeholder="Ej: 45%, 120 atenciones">
                                </div>
                            </div>
                        </div>

                        {{-- 13. Metas --}}
                        <div class="mb-2">
                            <div class="d-flex align-items-center mb-1">
                                <label class="ind-label mb-0"><span class="ind-num">13</span> Metas por Período</label>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 ml-auto" id="btnAgregarMeta" style="font-size:.72rem">
                                    <i class="fa fa-plus mr-1"></i> Agregar
                                </button>
                            </div>
                            <div id="metasContainer" class="row no-gutters" style="gap:.3rem 0"></div>
                        </div>

                    </div>{{-- /col izquierda --}}

                    {{-- ── Columna derecha ──────────────────────────────────────── --}}
                    <div class="col-md-6 px-4 py-3">

                        {{-- 5. Descripción --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">5</span> Descripción del Indicador</label>
                            <small class="d-block text-muted mb-1" style="font-size:.73rem">En qué consiste y qué permite medir.</small>
                            <textarea id="ind_descripcion" class="form-control form-control-sm" rows="3"
                                      placeholder="Describir qué mide y para qué sirve..."></textarea>
                        </div>

                        {{-- 6. Variables --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">6</span> Variables</label>
                            <small class="d-block text-muted mb-1" style="font-size:.73rem">Variables que componen la fórmula.</small>
                            <textarea id="ind_variables" class="form-control form-control-sm" rows="3"
                                      placeholder="A = ..., B = ..."></textarea>
                        </div>

                        {{-- 7-8. Fórmula y Unidad --}}
                        <div class="row mb-3">
                            <div class="col-7">
                                <label class="ind-label"><span class="ind-num">7</span> Fórmula de Cálculo</label>
                                <input type="text" id="ind_formula" class="form-control form-control-sm"
                                       placeholder="Ej: (A / B) × 100">
                            </div>
                            <div class="col-5">
                                <label class="ind-label"><span class="ind-num">8</span> Unidad de Medida</label>
                                <input type="text" id="ind_unidad_medida" class="form-control form-control-sm"
                                       placeholder="%, Nº, Tasa...">
                            </div>
                        </div>

                        {{-- 14. Fuente --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">14</span> Fuente(s) de Información</label>
                            <input type="text" id="ind_fuente" class="form-control form-control-sm"
                                   placeholder="Registros administrativos, informes, censos...">
                        </div>

                        {{-- 15. Responsable --}}
                        <div class="mb-3">
                            <label class="ind-label"><span class="ind-num">15</span> Dependencia Responsable</label>
                            <input type="text" id="ind_dependencia_responsable" class="form-control form-control-sm"
                                   placeholder="Dirección o unidad a cargo del cálculo y reporte">
                        </div>

                        {{-- 16. Comentarios --}}
                        <div class="mb-2">
                            <label class="ind-label"><span class="ind-num">16</span> Comentarios</label>
                            <textarea id="ind_comentarios" class="form-control form-control-sm" rows="3"
                                      placeholder="Observaciones adicionales sobre el indicador..."></textarea>
                        </div>

                    </div>{{-- /col derecha --}}

                </div>{{-- /row dos columnas --}}

            </div>{{-- /modal-body --}}

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-success" id="btnGuardarIndicador">
                    <i class="fa fa-save mr-1"></i> Guardar Ficha
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Estilos internos del modal --}}
<style>
.ind-label {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #343a40;
    margin-bottom: .35rem;
}
.ind-sublabel {
    display: block;
    font-size: .68rem;
    text-transform: uppercase;
    color: #6c757d;
    margin-bottom: .2rem;
}
.ind-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #1a237e;
    color: #fff;
    font-size: .65rem;
    font-weight: 700;
    margin-right: .35rem;
    flex-shrink: 0;
}
/* Cards de radio */
.ind-radio-card { cursor: pointer; }
.ind-card-inner  { transition: all .12s; background: #fff; }
.ind-radio-card:hover .ind-card-inner { background: #f5f5f5; }
/* Estado seleccionado — JS agrega .ind-selected */
.ind-radio-card.ind-selected-primary   .ind-card-inner { background:#e3f2fd; border-color:#1976d2!important; color:#1976d2; font-weight:600; }
.ind-radio-card.ind-selected-success   .ind-card-inner { background:#e8f5e9; border-color:#28a745!important; color:#28a745; font-weight:600; }
.ind-radio-card.ind-selected-info      .ind-card-inner { background:#e0f7fa; border-color:#17a2b8!important; color:#17a2b8; font-weight:600; }
.ind-radio-card.ind-selected-warning   .ind-card-inner { background:#fff8e1; border-color:#ffc107!important; color:#856404; font-weight:600; }
.ind-radio-card.ind-selected-dark      .ind-card-inner { background:#e9ecef; border-color:#343a40!important; color:#343a40; font-weight:600; }
.ind-radio-card.ind-selected-secondary .ind-card-inner { background:#f8f9fa; border-color:#6c757d!important; color:#495057; font-weight:600; }
.ind-radio-card.ind-selected-teal      .ind-card-inner { background:#e0f2f1; border-color:#0e7490!important; color:#0e7490; font-weight:600; }
.ind-radio-card.ind-selected-danger    .ind-card-inner { background:#fdecea; border-color:#dc3545!important; color:#dc3545; font-weight:600; }
</style>

<script>
if (typeof window.getRadioValue !== 'function') {
    window.getRadioValue = function(name) {
        // 1. Intentar por input checked nativo
        var $checked = $('input[name="' + name + '"]:checked');
        if ($checked.length && $checked.val()) return $checked.val();
        
        // 2. Intentar por ID directo si existiera (ej: #form_ind_dimension)
        var $byFormId = $('#form_' + name);
        if ($byFormId.length && $byFormId.val()) return $byFormId.val();

        // 3. Fallback: buscar por clase ind-selected-* en las cards si radio check se desincronizó
        var $selectedCard = $('input[name="' + name + '"]').closest('.ind-radio-card').filter(function() {
            var cls = $(this).attr('class') || '';
            return cls.indexOf('ind-selected-') >= 0;
        });
        if ($selectedCard.length) {
            var $r = $selectedCard.find('input[type="radio"]');
            $r.prop('checked', true);
            return $r.val();
        }
        return null;
    };
}

$(document).ready(function() {
    $(document).off('click.indRadioCard', '.ind-radio-card').on('click.indRadioCard', '.ind-radio-card', function(e) {
        var $radio = $(this).find('input[type="radio"]');
        if ($radio.length) {
            var name = $radio.attr('name');
            $('input[name="' + name + '"]').each(function() {
                $(this).prop('checked', false);
                var card = $(this).closest('.ind-radio-card');
                var color = card.data('color') || 'secondary';
                card.removeClass('ind-selected-' + color);
            });
            $radio.prop('checked', true).trigger('change');
            var color = $(this).data('color') || 'secondary';
            $(this).addClass('ind-selected-' + color);
        }
    });

    $(document).off('change.indRadio', '.ind-radio').on('change.indRadio', '.ind-radio', function() {
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
});
</script>
