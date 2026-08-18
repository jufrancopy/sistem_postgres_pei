{{-- Inicio Modales --}}
<div class="modal fade" id="ajaxMisionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white" id="modalHeadingMision"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="misionForm" name="misionForm" class="form-horizontal">

                    {{ Form::hidden('profile_id', null, ['id' => 'profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                    {{ Form::hidden('name', null, ['id' => 'name']) }}
                    {{ Form::hidden('year_start', null, ['id' => 'year_start']) }}
                    {{ Form::hidden('year_end', null, ['id' => 'year_end']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'group_id']) }}
                    {{ Form::hidden('type', null, ['id' => 'type']) }}
                    {{ Form::hidden('level', null, ['id' => 'level']) }}
                    {{ Form::hidden('nivel_label', null, ['id' => 'nivel_label']) }}
                    {{ Form::hidden('vision', null, ['class' => 'form-control', 'id' => 'vision']) }}
                    {{ Form::hidden('values', null, ['class' => 'form-control', 'id' => 'values']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'dependencies']) }}


                    <div class="mision mb-2">
                        {{ Form::label('mision', 'MISIÓN:', ['class' => 'control-label']) }}
                        {{ Form::textarea('mision', null, [
                            'class' => 'form-control editor',
                            'id' => 'mision',
                        ]) }}
                    </div>

                    <div class="form-group mision_analysts" style="display: none;">
                        {!! Form::select('analyst_id[]', [], null, [
                            'id' => 'mision_analysts',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnMision" value="create">Guardar
                            cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxVisionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white" id="modalHeadingVision"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="visionForm" name="visionForm" class="form-horizontal">

                    {{ Form::hidden('profile_id', null, ['id' => 'vision_profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'parent_id']) }}
                    {{ Form::hidden('name', null, ['id' => 'vision_name']) }}
                    {{ Form::hidden('year_start', null, ['id' => 'vision_year_start']) }}
                    {{ Form::hidden('year_end', null, ['id' => 'vision_year_end']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'vision_group_id']) }}
                    {{ Form::hidden('type', null, ['id' => 'vision_type']) }}
                    {{ Form::hidden('level', null, ['id' => 'vision_level']) }}
                    {{ Form::hidden('nivel_label', null, ['id' => 'vision_nivel_label']) }}
                    {{ Form::hidden('mision', null, ['id' => 'vision_mision']) }}
                    {{ Form::hidden('values', null, ['class' => 'form-control', 'id' => 'vision_values']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'vision_period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'vision_numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'vision_numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'vision_denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'gvision_oal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'vision_progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'vision_dependencies']) }}

                    <div class="vision mb-2">
                        {{ Form::label('vision', 'Visión:', ['class' => 'control-label']) }}
                        {{ Form::textarea('vision', null, [
                            'class' => 'form-control editor',
                            'id' => 'vision',
                        ]) }}
                    </div>

                    <div class="form-group vision_analysts" style="display: none;">
                        {!! Form::select('analyst_id[]', [], null, [
                            'id' => 'vision_analysts',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnVision" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxValuesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white" id="modalHeadingValues"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="valuesForm" name="valuesForm" class="form-horizontal">

                    {{ Form::hidden('profile_id', null, ['id' => 'values_profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'values_parent_id']) }}
                    {{ Form::hidden('name', null, ['id' => 'values_name']) }}
                    {{ Form::hidden('year_start', null, ['id' => 'values_year_start']) }}
                    {{ Form::hidden('year_end', null, ['id' => 'values_year_end']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'values_group_id']) }}
                    {{ Form::hidden('mision', null, ['id' => 'values_mision']) }}
                    {{ Form::hidden('vision', null, ['id' => 'values_vision']) }}
                    {{ Form::hidden('type', null, ['id' => 'values_type']) }}
                    {{ Form::hidden('level', null, ['id' => 'values_level']) }}
                    {{ Form::hidden('nivel_label', null, ['id' => 'values_nivel_label']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'values_period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'values_numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'values_numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'values_denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'values_goal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'values_progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'values_dependencies']) }}

                    <div class="values mb-2">
                        {{ Form::label('values', 'Visión:', ['class' => 'control-label']) }}
                        {{ Form::textarea('values', null, [
                            'class' => 'form-control editor',
                            'id' => 'values',
                        ]) }}
                    </div>

                    <div class="form-group values_analysts" style="display: none;">
                        {!! Form::select('analyst_id[]', [], null, [
                            'id' => 'values_analysts',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnValues" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxAxisModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <div>
                    <h5 class="modal-title text-white mb-0" id="modalHeadingAxis"></h5>
                    <small class="text-white" style="opacity:.8"><i class="fa fa-bullseye mr-1"></i> Objetivo Estratégico</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="axisForm" name="axisForm" class="form-horizontal">

                    {{ Form::hidden('profile_id', null, ['id' => 'axis_profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'axis_parent_id']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'axis_group_id']) }}
                    {{ Form::hidden('mision', null, ['id' => 'axis_mision']) }}
                    {{ Form::hidden('vision', null, ['id' => 'axis_vision']) }}
                    {{ Form::hidden('type', 'institucional', ['id' => 'axis_type']) }}
                    {{ Form::hidden('level', 'axi', ['id' => 'axi_level']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'axis_period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'axis_numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'axis_numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'axis_denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'axis_goal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'axis_progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'axis_dependency']) }}

                    {{-- Banner Asistente IA SIPLAN --}}
                    <div class="p-3 mb-3 rounded shadow-xs text-white d-flex align-items-center justify-content-between flex-wrap" style="background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 100%); gap:10px; border-radius:12px;">
                        <div>
                            <strong class="d-block" style="font-size:0.88rem;"><i class="fa fa-robot text-warning mr-1"></i> Asistente de Planificación IA (Llama 3.3 70B)</strong>
                            <small class="text-white-50" style="font-size:0.75rem;">Escribí una idea simple abajo y la IA completará la Acción Estratégica SMART y su Indicador.</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-warning font-weight-bold text-dark rounded-pill px-3 shadow-sm" id="btnGenerarTodoConIa" onclick="generarAccionEIndicadorConIa();">
                            <i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA
                        </button>
                    </div>

                    <div class="axis mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            {{ Form::label('name', 'Descripción del Objetivo Estratégico / Acción:', ['class' => 'control-label font-weight-bold mb-0']) }}
                            <button type="button" class="btn btn-xs btn-outline-success font-weight-bold rounded-pill px-2.5 shadow-xs" id="btnMejorarSmartIa" onclick="mejorarTextoSmartIa();" title="Usar la IA de Llama 3.3 para perfeccionar la redacción bajo metodología SMART e IPS">
                                <i class="fa fa-magic text-warning mr-1"></i> Mejorar Redacción SMART con IA
                            </button>
                        </div>
                        {{ Form::textarea('name', null, [
                            'class' => 'form-control editor',
                            'id' => 'axis',
                        ]) }}
                    </div>

                    <div class="order mb-3">
                        {{ Form::label('order_item', 'Orden:', ['class' => 'control-label']) }}
                        {{ Form::number('order_item', null, [
                            'class' => 'form-control',
                            'id' => 'axis_order_item',
                        ]) }}
                    </div>

                    {{-- ── Resultado Intermedio Institucional ── --}}
                    <div class="form-group mb-3">
                        <label class="control-label font-weight-bold">
                            <i class="fa fa-flag mr-1 text-success"></i> Resultado Intermedio Institucional
                            <span class="badge badge-light border ml-1" style="font-size:.68rem; font-weight:400">opcional</span>
                        </label>
                        <input type="text" id="axis_resultado_intermedio" name="resultado_intermedio"
                               class="form-control mt-1" autocomplete="off"
                               placeholder="Logro superior al que contribuye este objetivo...">
                        <small class="form-text text-muted">
                            Ej: "Asegurados y beneficiarios acceden a servicios de salud"
                        </small>
                        <div id="axis_ri_sugerencias" class="d-flex flex-wrap mt-1" style="gap:4px"></div>
                    </div>

                    {{-- ── Vinculación Presupuestaria del Resultado Intermedio (Acordeón) ── --}}
                    <div class="card border mb-3 shadow-none" id="ri_vinculacion_block" style="border-radius: 8px; border-color: #dee2e6;">
                        <div class="card-header bg-light d-flex align-items-center justify-content-between py-2 px-3"
                             style="cursor: pointer; user-select: none;"
                             data-toggle="collapse"
                             data-target="#riVinculacionCollapse"
                             aria-expanded="false"
                             aria-controls="riVinculacionCollapse">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-coins mr-2 text-warning"></i>
                                <span class="font-weight-bold text-dark" style="font-size:.8rem; letter-spacing:.02em">Vinculación Presupuestaria (2)</span>
                                <span class="badge badge-light border ml-2" style="font-size:.65rem; font-weight:400">opcional</span>
                            </div>
                            <div class="d-flex align-items-center text-muted">
                                <span class="mr-2 d-none d-sm-inline" style="font-size:.72rem;" id="ri_vinculacion_toggle_label">Clic para desplegar</span>
                                <i class="fa fa-chevron-down ri-chevron-icon" style="font-size:.72rem; transition: transform .2s ease;"></i>
                            </div>
                        </div>

                        <div id="riVinculacionCollapse" class="collapse">
                            <div class="card-body bg-white border-top">
                                <div class="mb-3">
                                    <label class="small font-weight-bold d-block" style="margin-bottom:.4rem">Resultado Intermedio Presupuestario (2.1)</label>
                                    <input type="text" id="axis_ri_presupuestario" name="ri_presupuestario"
                                           class="form-control form-control-sm"
                                           placeholder="Descripción del resultado presupuestario...">
                                    <small class="text-muted d-block" style="font-size:.72rem;margin-top:.3rem">
                                        Ej: Trabajadores dependientes que aportan al seguro social...
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="small font-weight-bold d-block" style="margin-bottom:.4rem">Programa Presupuestario (2.2)</label>
                                    <input type="text" id="axis_ri_programa" name="ri_programa"
                                           class="form-control form-control-sm"
                                           placeholder="Nombre del programa y actividad...">
                                    <small class="text-muted d-block" style="font-size:.72rem;margin-top:.3rem">
                                        Ej: Programa, Central — Actividad: Servicios de Prestaciones Sanitarias
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="small font-weight-bold d-block" style="margin-bottom:.4rem">Recursos Asignados (2.3) — Gs.</label>
                                    <input type="number" id="axis_ri_recursos_gs" name="ri_recursos_gs"
                                           class="form-control form-control-sm" min="0" step="1"
                                           placeholder="0">
                                    <small class="text-muted d-block" style="font-size:.72rem;margin-top:.3rem">
                                        Ingresar sin puntos ni comas. Ej: 6341255316165
                                    </small>
                                </div>

                                {{-- Metas dinámicas del Resultado Intermedio --}}
                                <div class="mb-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <label class="small font-weight-bold mb-0">
                                            <i class="fa fa-bullseye mr-1"></i> Metas del Resultado Intermedio
                                        </label>
                                        <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 ml-auto btnAgregarRiMetaBtn" style="font-size:.72rem">
                                            <i class="fa fa-plus mr-1"></i> Agregar período
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mb-2" style="font-size:.72rem">
                                        El valor puede ser porcentaje, número entero o decimal (ej: 85%, 1200, 3.5).
                                    </small>
                                    <div id="riMetasContainer" class="row no-gutters" style="gap:.3rem 0"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Estrategias del Cruce de Ambientes (FODA) ── --}}
                    <div class="form-group mb-3 position-relative">
                        {{ Form::label('axis_strategies', 'Estrategias del Cruce de Ambientes (FODA):', ['class' => 'control-label font-weight-bold']) }}
                        <small class="form-text text-muted mb-1">
                            Seleccioná las estrategias FO/FA/DO/DA que fundamentan esta estrategia institucional.
                        </small>
                        {!! Form::select('strategy_id[]', [], null, [
                            'id' => 'axis_strategies',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    {{-- ── Marcos Referenciales (PND, ODS, etc.) ── --}}
                    <div class="form-group mb-3 position-relative">
                        <label class="control-label font-weight-bold d-flex align-items-center justify-content-between">
                            <span><i class="fa fa-link mr-1"></i> Marcos Referenciales</span>
                            <button type="button" class="btn btn-xs btn-outline-success font-weight-bold rounded-pill px-2.5 shadow-xs" onclick="abrirModalInspiracionOds(3);">
                                <i class="fa fa-lightbulb text-warning mr-1"></i> Banco de Ideas ODS (ONU)
                            </button>
                        </label>
                        <small class="form-text text-muted mb-1">
                            Vinculá este objetivo a PND 2050, ODS u otro marco normativo.
                            Si no existe, escribilo y se creará automáticamente.
                        </small>
                        <select id="axis_marcos" name="marco_id[]" style="width:100%" multiple></select>
                    </div>

                    {{-- ── Perspectiva BSC (opcional) ── --}}
                    <div class="form-group mb-3 position-relative" id="axis_bsc_block" style="{{ isset($niveles['bsc_level']) && ($niveles['bsc_level'] === 'goal' || $niveles['bsc_level'] === 'none') ? 'display:none !important;' : '' }}">
                        <label class="control-label font-weight-bold">
                            <i class="fa fa-chart-bar mr-1 text-primary"></i> Perspectiva BSC
                            <span class="badge badge-light border ml-1" style="font-size:.68rem; font-weight:400">opcional</span>
                        </label>
                        <small class="form-text text-muted mb-1">
                            Si el plan usa el modelo Balanced Scorecard, clasificá este objetivo en su perspectiva correspondiente.
                        </small>
                        <select id="axis_bsc_perspectiva" name="bsc_perspectiva" class="form-control" style="width:100%">
                            <option value="">— Sin perspectiva BSC —</option>
                            <option value="financiera">💰 Perspectiva Financiera</option>
                            <option value="clientes">👥 Perspectiva de Clientes / Usuarios</option>
                            <option value="procesos">⚙️ Perspectiva de Procesos Internos</option>
                            <option value="aprendizaje">📚 Perspectiva de Aprendizaje y Crecimiento</option>
                        </select>
                    </div>

                    {{-- ── Indicador de la Ficha Técnica (Ámbito: Objetivo Estratégico) ── --}}
                    <hr class="my-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="font-weight-bold mb-0">
                            <i class="fa fa-ruler-combined mr-1 text-primary"></i> Indicador
                        </h6>
                        <a href="{{ route('pei.indicadores.modulo', $profile->id) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary py-0 px-2 ml-auto"
                           style="font-size:.72rem" title="Gestionar fichas de indicadores">
                            <i class="fa fa-external-link-alt mr-1"></i> Gestionar Indicadores
                        </a>
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <small class="form-text text-muted mb-1">
                            Seleccioná el indicador de la ficha técnica que mide este objetivo estratégico (ámbito: Objetivo Estratégico).
                        </small>
                        <select id="axis_indicador_id" name="indicador_id" style="width:100%"></select>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10 mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnAxis" value="create">
                            <i class="fa fa-save mr-1"></i> Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxGoalsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header" style="background:linear-gradient(135deg,#1565c0,#1976d2)">
                <div>
                    <h5 class="modal-title text-white mb-0" id="modalHeadingGoals"></h5>
                    <small class="text-white" style="opacity:.8"><i class="fa fa-flag-checkered mr-1"></i> Meta</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="goalsForm" name="goalsForm" class="form-horizontal">
                    {{ Form::hidden('profile_id', null, ['id' => 'goals_profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'goals_parent_id']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'goals_group_id']) }}
                    {{ Form::hidden('mision', null, ['id' => 'goals_mision']) }}
                    {{ Form::hidden('vision', null, ['id' => 'goals_vision']) }}
                    {{ Form::hidden('type', 'institucional', ['id' => 'goals_type']) }}
                    {{ Form::hidden('level', 'goal', ['id' => 'goals_level']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'goals_period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'goals_numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'goals_numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'goals_denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'goals_goal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'goals_progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'goals_dependency']) }}

                    <div class="goal mb-3">
                        {{ Form::label('name', 'Descripción de la Meta:', ['class' => 'control-label font-weight-bold']) }}
                        {{ Form::textarea('name', null, [
                            'class' => 'form-control editor',
                            'id' => 'goals',
                        ]) }}
                    </div>

                    <div class="goals_order_item mb-3">
                        {{ Form::label('goals_order_item', 'Orden:', ['class' => 'control-label']) }}
                        {{ Form::number('order_item', null, [
                            'class' => 'form-control',
                            'id' => 'goals_order_item',
                        ]) }}
                    </div>

                    {{-- ── Perspectiva BSC (opcional en Nivel 2) ── --}}
                    <div class="form-group mb-3" id="goals_bsc_block" style="display:none;">
                        <label class="control-label font-weight-bold">
                            <i class="fa fa-chart-bar mr-1 text-primary"></i> Perspectiva BSC
                            <span class="badge badge-light border ml-1" style="font-size:.68rem; font-weight:400">opcional</span>
                        </label>
                        <small class="form-text text-muted mb-1">
                            Si el plan usa el modelo Balanced Scorecard a este nivel, clasificá este objetivo en su perspectiva correspondiente.
                        </small>
                        <select id="goals_bsc_perspectiva" name="bsc_perspectiva" class="form-control" style="width:100%">
                            <option value="">— Sin perspectiva BSC —</option>
                            <option value="financiera">💰 Perspectiva Financiera</option>
                            <option value="clientes">👥 Perspectiva de Clientes / Usuarios</option>
                            <option value="procesos">⚙️ Perspectiva de Procesos Internos</option>
                            <option value="aprendizaje">📚 Perspectiva de Aprendizaje y Crecimiento</option>
                        </select>
                    </div>

                    {{-- ── Indicador de la Ficha Técnica (Ámbito: Objetivo Específico) ── --}}
                    <hr class="my-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="font-weight-bold mb-0">
                            <i class="fa fa-ruler-combined mr-1 text-primary"></i> Indicador
                        </h6>
                        <a href="{{ route('pei.indicadores.modulo', $profile->id) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary py-0 px-2 ml-auto"
                           style="font-size:.72rem" title="Gestionar fichas de indicadores">
                            <i class="fa fa-external-link-alt mr-1"></i> Gestionar Indicadores
                        </a>
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <small class="form-text text-muted mb-1">
                            Seleccioná el indicador de la ficha técnica que mide este objetivo específico (ámbito: Objetivo Específico).
                        </small>
                        <select id="goals_indicador_id" name="indicador_id" style="width:100%"></select>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10 mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnGoals" value="create">
                            <i class="fa fa-save mr-1"></i> Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxStrategiesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#e65100,#f57c00)">
                <h5 class="modal-title text-white" id="modalHeadingStrategies"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="strategiesList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Estrategia</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxActionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header" style="background:linear-gradient(135deg,#2e7d32,#388e3c)">
                <div>
                    <h5 class="modal-title text-white mb-0" id="modalHeadingActions"></h5>
                    <small class="text-white" style="opacity:.8"><i class="fa fa-rocket mr-1"></i> {{ $niveles['action'] ?? 'Acción' }}</small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="actionsForm" name="actionsForm" class="form-horizontal">

                    {{ Form::hidden('profile_id', null, ['id' => 'actions_profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'actions_parent_id']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'actions_group_id']) }}
                    {{ Form::hidden('mision', null, ['id' => 'actions_mision']) }}
                    {{ Form::hidden('vision', null, ['id' => 'actions_vision']) }}
                    {{ Form::hidden('type', 'institucional', ['id' => 'actions_type']) }}
                    {{ Form::hidden('level', 'action', ['id' => 'actions_level']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'actions_period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'actions_numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'actions_numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'actions_denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'actions_goal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'actions_progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'actions_dependency']) }}

                    {{-- Banner Asistente IA SIPLAN --}}
                    <div class="p-3 mb-3 rounded shadow-xs text-white d-flex align-items-center justify-content-between flex-wrap" style="background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 100%); gap:10px; border-radius:12px;">
                        <div>
                            <strong class="d-block" style="font-size:0.88rem;"><i class="fa fa-robot text-warning mr-1"></i> Asistente de Planificación IA (Llama 3.3 70B)</strong>
                            <small class="text-white-50" style="font-size:0.75rem;">Escribí una idea simple abajo y la IA completará la Acción Estratégica SMART y su Indicador.</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-warning font-weight-bold text-dark rounded-pill px-3 shadow-sm" id="btnGenerarTodoConIaActions" onclick="generarAccionEIndicadorConIaActions();">
                            <i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA
                        </button>
                    </div>

                    <div class="actions mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            {{ Form::label('name', 'Descripción de la ' . ($niveles['action'] ?? 'Acción') . ':', ['class' => 'control-label font-weight-bold mb-0']) }}
                            <button type="button" class="btn btn-xs btn-outline-success font-weight-bold rounded-pill px-2.5 shadow-xs" id="btnMejorarSmartIaActions" onclick="mejorarTextoSmartIaActions();" title="Usar la IA de Llama 3.3 para perfeccionar la redacción bajo metodología SMART e IPS">
                                <i class="fa fa-magic text-warning mr-1"></i> Mejorar Redacción SMART con IA
                            </button>
                        </div>
                        {{ Form::textarea('name', null, [
                            'class' => 'form-control editor',
                            'id' => 'actions',
                        ]) }}
                    </div>

                    <div class="actions_order_item mb-2">
                        {{ Form::label('actions_order_item', 'Orden:', ['class' => 'control-label']) }}
                        {{ Form::number('order_item', null, [
                            'class' => 'form-control',
                            'id' => 'actions_order_item',
                        ]) }}
                    </div>

                    {{-- ── Indicador de la Ficha Técnica ── --}}
                    <hr class="my-3">
                    <div class="d-flex align-items-center mb-2">
                        <h6 class="font-weight-bold mb-0">
                            <i class="fa fa-ruler-combined mr-1 text-primary"></i> Indicador
                        </h6>
                        <a href="{{ route('pei.indicadores.modulo', $profile->id) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary py-0 px-2 ml-auto"
                           style="font-size:.72rem" title="Gestionar fichas de indicadores">
                            <i class="fa fa-external-link-alt mr-1"></i> Gestionar Indicadores
                        </a>
                    </div>
                    <div class="form-group mb-3 position-relative">
                        <small class="form-text text-muted mb-1">
                            Seleccioná el indicador de la ficha técnica que mide esta acción.
                            Si no existe aún, crealo desde "Gestionar Indicadores".
                        </small>
                        <select id="action_indicador_id" name="indicador_id" style="width:100%"></select>

                    </div>
                    <hr class="my-3">

                    <div class="form-group">
                        {{ Form::label('responsibles', 'Asignar Responsables:') }}
                        {!! Form::select('responsible_id[]', [], null, [
                            'id' => 'responsibles',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    {{-- ── Vinculación PGN ── --}}
                    <hr class="my-3">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fa fa-coins mr-1"></i> Vinculación Presupuestaria (PGN)
                    </h6>

                    <div class="form-group">
                        <label class="control-label">Actividad PGN</label>
                        <small class="form-text text-muted mb-1">Buscá por código o nombre. Solo nodos hoja del año activo.</small>
                        <select id="action_pgn_nodo" name="pgn_nodo_id" style="width:100%"></select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Resultado</label>
                        <input type="text" class="form-control" id="action_pgn_resultado" name="pgn_resultado"
                            placeholder="Ej: Resultado 1.1 — Servicios de salud fortalecidos">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Monto Vinculado (Gs.)</label>
                                <input type="number" class="form-control" id="action_pgn_monto_vinculado"
                                    name="pgn_monto_vinculado" min="0" step="1" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Monto Ejecutado (Gs.)</label>
                                <input type="number" class="form-control" id="action_pgn_monto_ejecutado"
                                    name="pgn_monto_ejecutado" min="0" step="1" placeholder="0">
                            </div>
                        </div>
                    </div>

                    {{-- ── Actividad vinculada ── --}}
                    <hr class="my-3">
                    <h6 class="font-weight-bold mb-2">
                        <i class="fa fa-tasks mr-1 text-success"></i> Actividad de Gestión
                    </h6>
                    <div class="form-group mb-2">
                        <div class="d-flex align-items-center" style="gap:.5rem">
                            <input type="checkbox" id="action_cuenta_actividad" style="width:16px;height:16px;cursor:pointer;flex-shrink:0">
                            <label for="action_cuenta_actividad" class="mb-0 small font-weight-bold" style="cursor:pointer">
                                Esta acción cuenta con una Actividad
                            </label>
                        </div>
                    </div>
                    <div id="action_actividad_panel" style="display:none">
                        <div class="btn-group btn-group-sm mb-2" role="group">
                            <button type="button" class="btn btn-outline-success active" id="btnActividadNueva">
                                <i class="fa fa-plus mr-1"></i>Crear nueva
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btnActividadExistente">
                                <i class="fa fa-search mr-1"></i>Vincular existente
                            </button>
                        </div>
                        <div id="panel_nueva_actividad">
                            <div class="form-group mb-1">
                                <input type="text" class="form-control form-control-sm" id="action_actividad_nombre"
                                    placeholder="Nombre (por defecto: nombre de la acción)">
                            </div>
                            <button type="button" class="btn btn-sm btn-success" id="btnCrearActividad">
                                <i class="fa fa-plus mr-1"></i>Crear y vincular
                            </button>
                        </div>
                        <div id="panel_existente_actividad" style="display:none">
                            <select id="action_activity_id_select" style="width:100%"></select>
                        </div>
                        <div id="panel_actividad_vinculada" class="mt-2" style="display:none">
                            <div class="d-flex align-items-center flex-wrap" style="gap:.4rem">
                                <span class="badge badge-success p-2" style="font-size:.8rem">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    <span id="action_actividad_nombre_vinculada"></span>
                                </span>
                                <a id="action_actividad_link" href="#" target="_blank"
                                   class="btn btn-sm btn-outline-success" style="font-size:.72rem">
                                    <i class="fa fa-external-link-alt mr-1"></i>Abrir tablero
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnDesvincularActividad"
                                    style="font-size:.72rem">
                                    <i class="fa fa-unlink mr-1"></i>Desvincular
                                </button>
                            </div>
                            {{-- Selector de tareas de la actividad --}}
                            <div class="mt-2">
                                <label class="small font-weight-bold mb-1">
                                    <i class="fa fa-check-square mr-1 text-success"></i> Tareas vinculadas a esta acción
                                </label>
                                <select id="action_activity_tasks" name="activity_task_ids[]" style="width:100%" multiple></select>
                                <small class="text-muted" style="font-size:.72rem">Seleccioná una o varias tareas de la actividad.</small>
                            </div>
                        </div>
                    </div>
                    {{ Form::hidden('activity_id', null, ['id' => 'actions_activity_id']) }}

                    <div class="col-sm-offset-2 col-sm-10 mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnActions" value="create">
                            <i class="fa fa-save mr-1"></i> Guardar cambios
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxHistoricalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white" id="modalHeadingHistorical"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="compareList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Definicion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxAxisListlModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white" id="modalHeadingAxisList"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="axisList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Objetivo Estratégico</th>
                                <th>Estrategias FODA vinculadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxGoalsListModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1565c0,#1976d2)">
                <h5 class="modal-title text-white" id="modalHeadingGoalsList"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="goalsList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Meta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxActionsListModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#2e7d32,#388e3c)">
                <h5 class="modal-title text-white" id="modalHeadingActionsList"></h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="actionsList">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Acción</th>
                                <th>Indicador</th>
                                <th>Línea de Base</th>
                                <th>Meta</th>
                                <th>Responsable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times mr-1"></i>Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxDefineCriteriaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#1b5e20,#2e7d32)">
                <div>
                    <h5 class="modal-title text-white mb-0" id="modalReportProgress">
                        <i class="fa fa-chart-line mr-2"></i>Reportar Avance
                    </h5>
                    <small class="text-white" style="opacity:.75;font-size:.75rem" id="reportProgress_accionNombre"></small>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="reportProgress_accionId">

                {{-- Ficha del indicador (readonly) --}}
                <div id="reportProgress_fichaIndicador" class="mb-3 p-2 rounded" style="display:none;background:#f0f4ff;border:1px solid #c5cae9;font-size:.8rem">
                    <div class="d-flex align-items-center flex-wrap mb-1" style="gap:.3rem">
                        <span class="badge badge-dark" id="rp_ind_codigo" style="font-size:.65rem"></span>
                        <span class="badge" id="rp_ind_dimension" style="font-size:.65rem"></span>
                        <span id="rp_ind_sentido"></span>
                        <strong id="rp_ind_nombre"></strong>
                    </div>
                    <div class="row" style="font-size:.76rem">
                        <div class="col-md-6">
                            <span class="text-muted">Fórmula: </span><span id="rp_ind_formula" style="font-style:italic"></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted">Unidad: </span><span id="rp_ind_unidad"></span>
                        </div>
                    </div>
                    <div id="rp_ind_variables_container" class="mt-1 text-muted" style="font-size:.73rem; display:none;">
                        <i class="fa fa-calculator text-primary mr-1"></i><strong>Variables:</strong> <span id="rp_ind_variables" class="text-dark font-italic"></span>
                    </div>
                    <div class="mt-1">
                        <span class="text-muted" style="font-size:.72rem">Meta del año actual: </span>
                        <strong id="rp_ind_meta_anio" style="font-size:.78rem;color:#1a237e"></strong>
                    </div>
                </div>

                {{-- Formulario de reporte --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Fecha del reporte <span class="text-danger">*</span></label>
                            <input type="date" id="rp_fecha_reporte" class="form-control"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Período</label>
                            <input type="text" id="rp_periodo_label" class="form-control"
                                   placeholder="Ej: Ene-Jun 2025, 1er Trim 2025...">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold small">
                        Valor logrado <span class="text-muted font-weight-normal">(numerador del indicador)</span>
                    </label>
                    <div class="input-group">
                        <input type="number" id="rp_valor_numerador" class="form-control"
                               step="0.0001" placeholder="Ej: 85 (si la meta es 100%)">
                        <div class="input-group-append">
                            <span class="input-group-text" id="rp_unidad_label" style="font-size:.8rem">—</span>
                        </div>
                    </div>
                    {{-- Semáforo preview en tiempo real --}}
                    <div id="rp_semaforo_preview" class="mt-1" style="display:none">
                        <span class="badge" id="rp_semaforo_badge" style="font-size:.78rem"></span>
                        <span class="text-muted ml-1" id="rp_pct_label" style="font-size:.75rem"></span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold small">Descripción del avance</label>
                    <textarea id="rp_descripcion_avance" class="form-control" rows="3"
                              placeholder="Describí qué se logró, qué está en progreso y qué obstáculos encontraste..."></textarea>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold small">Evidencia <span class="text-muted font-weight-normal">(opcional)</span></label>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" id="rp_evidencia_url" class="form-control form-control-sm"
                                   placeholder="URL del documento, informe, acta...">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="rp_evidencia_label" class="form-control form-control-sm"
                                   placeholder="Descripción breve">
                        </div>
                    </div>
                </div>

                {{-- Historial de reportes anteriores --}}
                <div class="mt-3">
                    <div class="d-flex align-items-center mb-1">
                        <small class="text-uppercase font-weight-bold text-muted" style="font-size:.65rem;letter-spacing:.04em">
                            <i class="fa fa-history mr-1"></i> Reportes anteriores
                        </small>
                        <span class="badge badge-light border ml-2" id="rp_historial_count" style="font-size:.65rem">0</span>
                    </div>
                    <div id="rp_historial" style="max-height:200px;overflow-y:auto">
                        <p class="text-muted text-center" style="font-size:.78rem">Sin reportes previos.</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-success" id="btnGuardarReporte">
                    <i class="fa fa-save mr-1"></i> Guardar Reporte
                </button>
            </div>
        </div>
    </div>
</div>
{{-- Fin Modales --}}

{{-- ══ Modal Ficha Técnica de Indicador (reutilizable) ═══════════════════ --}}
@include('admin.planificacion.indicadores.modal_ficha', ['profile' => $profile])

{{-- ══ Modal Lista de Indicadores del Perfil ══════════════════════════════ --}}
<div class="modal fade" id="modalIndicadoresList" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a237e,#283593)">
                <h5 class="modal-title text-white mb-0">
                    <i class="fa fa-ruler-combined mr-2"></i> Indicadores del Plan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-sm btn-success" id="btnNuevoIndicadorDesdeList">
                        <i class="fa fa-plus mr-1"></i> Nuevo Indicador
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="tablaIndicadoresList">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:80px">Código</th>
                                <th>Nombre</th>
                                <th style="width:100px">Dimensión</th>
                                <th style="width:120px">Ámbito</th>
                                <th style="width:90px">Frecuencia</th>
                                <th style="width:80px">Sentido</th>
                                <th style="width:90px" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="indicadoresListBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
{{-- /Modal Lista --}}

<script>
function mejorarTextoSmartIa() {
    var rawText = $('#axis').val();
    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['axis']) {
        rawText = CKEDITOR.instances['axis'].getData();
    }

    var plainText = $('<div>').html(rawText).text().trim();
    if (!plainText) {
        if (typeof toastr !== 'undefined') toastr.warning('Ingresá una idea o borrador primero para que la IA lo redacte en formato SMART.');
        return;
    }

    var $btn = $('#btnMejorarSmartIa');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Redactando con IA...');

    $.ajax({
        url: '{{ route("admin.ai.redactarSmart") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            texto: plainText,
            tipo: 'Objetivo/Acción Estratégica IPS'
        },
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-magic text-warning mr-1"></i> Mejorar Redacción SMART con IA');
            if (res.success && res.resultado) {
                if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['axis']) {
                    CKEDITOR.instances['axis'].setData(res.resultado);
                } else {
                    $('#axis').val(res.resultado);
                }
                if (typeof toastr !== 'undefined') toastr.success('¡Texto mejorado y formateado a metodología SMART por la IA!');
            }
        },
        error: function(xhr) {
            $btn.prop('disabled', false).html('<i class="fa fa-magic text-warning mr-1"></i> Mejorar Redacción SMART con IA');
            if (typeof toastr !== 'undefined') toastr.error('Error al conectar con la IA.');
        }
    });
}

function generarAccionEIndicadorConIa() {
    var rawText = $('#axis').val();
    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['axis']) {
        rawText = CKEDITOR.instances['axis'].getData();
    }
    var borrador = $('<div>').html(rawText).text().trim();

    if (!borrador || borrador.length < 3) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Escribí una idea básica primero',
                text: 'Por favor, ingresá una idea o borrador breve en la casilla de descripción (ej: "Construir nuevo vacunatorio en Luque") y volvé a presionar el botón.',
                confirmButtonColor: '#4f46e5'
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.warning('Escribí una idea o borrador primero en la descripción.');
        }
        return;
    }

    var $btn = $('#btnGenerarTodoConIa');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando con IA (Llama 3.3)...');

    $.ajax({
        url: '{{ route("admin.ai.generarAccionCompleta") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            borrador: borrador
        },
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA');
            if (res.success && res.data) {
                var d = res.data;
                if (d.accion_nombre) {
                    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['axis']) {
                        CKEDITOR.instances['axis'].setData(d.accion_nombre);
                    } else {
                        $('#axis').val(d.accion_nombre);
                    }
                }
                if (d.resultado_intermedio && $('#axis_resultado_intermedio').length) {
                    $('#axis_resultado_intermedio').val(d.resultado_intermedio);
                }

                // Autocompletar Ficha Técnica de Indicador en modal_ficha
                if (d.indicador) {
                    var ind = d.indicador;
                    if ($('#form_ind_nombre').length) $('#form_ind_nombre').val(ind.nombre);
                    if ($('#form_ind_codigo_letras').length) $('#form_ind_codigo_letras').val(ind.codigo_letras);
                    if ($('#form_ind_codigo_numeros').length) $('#form_ind_codigo_numeros').val(ind.codigo_numeros);
                    if ($('#form_ind_formula').length) $('#form_ind_formula').val(ind.formula);
                    if ($('#form_ind_unidad_medida').length) $('#form_ind_unidad_medida').val(ind.unidad_medida);
                    if ($('#form_ind_fuente').length) $('#form_ind_fuente').val(ind.fuente);
                    if ($('#form_ind_dependencia_responsable').length) $('#form_ind_dependencia_responsable').val(ind.dependencia_responsable);

                    // Seleccionar radios de dimensión, frecuencia, cobertura y sentido
                    if (ind.dimension) {
                        $('input[name="ind_dimension"][value="' + ind.dimension + '"]').prop('checked', true).trigger('change');
                        $('.ind-card-dim').removeClass('border-primary bg-light');
                        $('.ind-card-dim[data-value="' + ind.dimension + '"]').addClass('border-primary bg-light');
                    }
                    if (ind.frecuencia) {
                        $('input[name="ind_frecuencia"][value="' + ind.frecuencia + '"]').prop('checked', true).trigger('change');
                    }
                    if (ind.cobertura) {
                        $('input[name="ind_cobertura"][value="' + ind.cobertura + '"]').prop('checked', true).trigger('change');
                    }
                    if (ind.sentido) {
                        $('input[name="ind_sentido"][value="' + ind.sentido + '"]').prop('checked', true).trigger('change');
                    }
                }

                if (typeof toastr !== 'undefined') {
                    toastr.success('¡Acción Estratégica SMART y su Ficha Técnica de Indicador fueron generadas exitosamente!', '🤖 IA Llama 3.3 70B');
                }
            }
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA');
            if (typeof toastr !== 'undefined') toastr.error('Ocurrió un error al conectar con la IA.');
        }
    });
}

function getValFromEditor(id, modalId) {
    var val = '';
    var instanceName = id + 'Editor';
    if (typeof window[instanceName] !== 'undefined' && window[instanceName] && typeof window[instanceName].getData === 'function') {
        val = window[instanceName].getData();
    } else if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances[id]) {
        val = CKEDITOR.instances[id].getData();
    } else {
        val = $('#' + id).val() || '';
    }

    var plain = $('<div>').html(val).text().trim();
    if (!plain && modalId) {
        var domText = $('#' + modalId + ' .ck-editor__editable').text().trim();
        if (domText) plain = domText;
    }
    return plain;
}

function setValToEditor(id, modalId, htmlContent) {
    var instanceName = id + 'Editor';
    if (typeof window[instanceName] !== 'undefined' && window[instanceName] && typeof window[instanceName].setData === 'function') {
        window[instanceName].setData(htmlContent);
    } else if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances[id]) {
        CKEDITOR.instances[id].setData(htmlContent);
    }
    $('#' + id).val(htmlContent);
    if (modalId && $('#' + modalId + ' .ck-editor__editable').length) {
        $('#' + modalId + ' .ck-editor__editable').html('<p>' + htmlContent + '</p>');
    }
}

function mejorarTextoSmartIaActions() {
    var plainText = getValFromEditor('actions', 'ajaxActionsModal');
    if (!plainText || plainText.length < 2) {
        plainText = "Optimización de la atención médica, gestión de turnos y abastecimiento de insumos en el IPS";
    }

    var $btn = $('#btnMejorarSmartIaActions');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Redactando con IA...');

    $.ajax({
        url: '{{ route("admin.ai.redactarSmart") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            texto: plainText,
            tipo: 'Acción Estratégica / Operativa IPS'
        },
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-magic text-warning mr-1"></i> Mejorar Redacción SMART con IA');
            if (res.success && res.resultado) {
                setValToEditor('actions', 'ajaxActionsModal', res.resultado);
                if (typeof toastr !== 'undefined') toastr.success('¡Texto mejorado y formateado a metodología SMART por la IA!');
            }
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="fa fa-magic text-warning mr-1"></i> Mejorar Redacción SMART con IA');
            if (typeof toastr !== 'undefined') toastr.error('Error al conectar con la IA.');
        }
    });
}

function generarAccionEIndicadorConIaActions() {
    var borrador = getValFromEditor('actions', 'ajaxActionsModal');
    if (!borrador || borrador.length < 2) {
        borrador = "Optimización de la atención médica, gestión de turnos y abastecimiento de insumos en el IPS";
    }

    var $btn = $('#btnGenerarTodoConIaActions');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Generando con IA (Llama 3.3)...');

    $.ajax({
        url: '{{ route("admin.ai.generarAccionCompleta") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            borrador: borrador
        },
        dataType: 'json',
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA');
            if (res.success && res.data) {
                var d = res.data;
                if (d.accion_nombre) {
                    setValToEditor('actions', 'ajaxActionsModal', d.accion_nombre);
                }
                if (d.resultado_intermedio && $('#actions_resultado_intermedio').length) {
                    $('#actions_resultado_intermedio').val(d.resultado_intermedio);
                }

                // Autocompletar Ficha Técnica de Indicador en modal_ficha si está presente
                if (d.indicador) {
                    var ind = d.indicador;
                    if ($('#form_ind_nombre').length) $('#form_ind_nombre').val(ind.nombre);
                    if ($('#form_ind_codigo_letras').length) $('#form_ind_codigo_letras').val(ind.codigo_letras);
                    if ($('#form_ind_codigo_numeros').length) $('#form_ind_codigo_numeros').val(ind.codigo_numeros);
                    if ($('#form_ind_formula').length) $('#form_ind_formula').val(ind.formula);
                    if ($('#form_ind_unidad_medida').length) $('#form_ind_unidad_medida').val(ind.unidad_medida);
                    if ($('#form_ind_fuente').length) $('#form_ind_fuente').val(ind.fuente);
                    if ($('#form_ind_dependencia_responsable').length) $('#form_ind_dependencia_responsable').val(ind.dependencia_responsable);

                    if (ind.dimension) {
                        $('input[name="ind_dimension"][value="' + ind.dimension + '"]').prop('checked', true).trigger('change');
                        $('.ind-card-dim').removeClass('border-primary bg-light');
                        $('.ind-card-dim[data-value="' + ind.dimension + '"]').addClass('border-primary bg-light');
                    }
                    if (ind.frecuencia) {
                        $('input[name="ind_frecuencia"][value="' + ind.frecuencia + '"]').prop('checked', true).trigger('change');
                    }
                    if (ind.cobertura) {
                        $('input[name="ind_cobertura"][value="' + ind.cobertura + '"]').prop('checked', true).trigger('change');
                    }
                    if (ind.sentido) {
                        $('input[name="ind_sentido"][value="' + ind.sentido + '"]').prop('checked', true).trigger('change');
                    }
                }

                if (typeof toastr !== 'undefined') {
                    toastr.success('¡Acción Estratégica SMART y su Ficha Técnica de Indicador fueron generadas exitosamente!', '🤖 IA Llama 3.3 70B');
                }
            }
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA');
            if (typeof toastr !== 'undefined') toastr.error('Ocurrió un error al conectar con la IA.');
        }
    });
}
</script>
