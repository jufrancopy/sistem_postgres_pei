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

                    @if(auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin') || auth()->user()->id == 1))
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
                    @endif

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
                    {{-- ── Junta Consultiva (Consejo de Sabios) ── --}}
                    <div class="form-group mb-3 p-3 rounded" style="background:#f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #7c3aed;">
                        <label class="control-label font-weight-bold d-flex align-items-center text-dark mb-1">
                            <i class="fas fa-landmark mr-2" style="color:#7c3aed"></i> Remitir a Junta Consultiva
                            <span class="badge badge-light border ml-2" style="font-size:.68rem; font-weight:400">supervisión institucional</span>
                        </label>
                        <small class="form-text text-muted mb-2">
                            Seleccioná la Junta Especial encargada de dictaminar sobre este objetivo estratégico.
                        </small>
                        @php
                            $juntasConsultivas = \App\Models\Planificacion\Junta::where('activo', true)->orderBy('nombre')->get();
                        @endphp
                        <select id="axis_junta_id" name="junta_id" class="form-control form-control-sm" style="width:100%">
                            <option value="">— Ninguna (Por defecto según área) —</option>
                            @foreach($juntasConsultivas as $junta)
                                <option value="{{ $junta->id }}">{{ $junta->codigo }} - {{ $junta->nombre }}</option>
                            @endforeach
                        </select>
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
                    {{-- ── Junta Consultiva (Consejo de Sabios) ── --}}
                    <div class="form-group mb-3 p-3 rounded" style="background:#f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #7c3aed;">
                        <label class="control-label font-weight-bold d-flex align-items-center text-dark mb-1">
                            <i class="fas fa-landmark mr-2" style="color:#7c3aed"></i> Remitir a Junta Consultiva
                            <span class="badge badge-light border ml-2" style="font-size:.68rem; font-weight:400">supervisión institucional</span>
                        </label>
                        <small class="form-text text-muted mb-2">
                            Seleccioná la Junta Especial encargada de dictaminar sobre este objetivo específico.
                        </small>
                        <select id="goals_junta_id" name="junta_id" class="form-control form-control-sm" style="width:100%">
                            <option value="">— Ninguna (Por defecto según área) —</option>
                            @foreach($juntasConsultivas as $junta)
                                <option value="{{ $junta->id }}">{{ $junta->codigo }} - {{ $junta->nombre }}</option>
                            @endforeach
                        </select>
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

            <div class="modal-header py-3" style="background:linear-gradient(135deg,#2e7d32,#388e3c)">
                <div style="flex:1; min-width:0; padding-right:15px;">
                    <h5 class="modal-title text-white font-weight-bold mb-1" id="modalHeadingActions" style="font-size:1.15rem;"></h5>
                    <div id="modalActionsSubdetail" class="text-white small" style="opacity:.95; font-size:0.85rem; line-height:1.3; font-weight:500;">
                        <i class="fa fa-rocket mr-1 text-warning"></i> {{ $niveles['action'] ?? 'Acción' }}
                    </div>
                </div>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
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
                    {{ Form::hidden('creado_con_ia', '0', ['id' => 'action_creado_con_ia']) }}

                    @if(auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin') || auth()->user()->id == 1))
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
                    @endif

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

                    {{-- ── Junta Consultiva (Consejo de Sabios) ── --}}
                    <div class="form-group mb-3 p-3 rounded" style="background:#f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #7c3aed;">
                        <label class="control-label font-weight-bold d-flex align-items-center text-dark mb-1">
                            <i class="fas fa-landmark mr-2" style="color:#7c3aed"></i> Remitir a Junta Consultiva
                            <span class="badge badge-light border ml-2" style="font-size:.68rem; font-weight:400">supervisión operacional</span>
                        </label>
                        <small class="form-text text-muted mb-2">
                            Seleccioná la Junta Especial encargada de dictaminar o acompañar esta Acción Estratégica.
                        </small>
                        <select id="actions_junta_id" name="junta_id" class="form-control form-control-sm" style="width:100%">
                            <option value="">— Ninguna (Por defecto según área) —</option>
                            @foreach($juntasConsultivas as $junta)
                                <option value="{{ $junta->id }}">{{ $junta->codigo }} - {{ $junta->nombre }}</option>
                            @endforeach
                        </select>
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
    if (!plainText || plainText.length < 2) {
        plainText = "Fortalecimiento de la gestión operativa e institucional de los servicios de salud y prestaciones del Instituto de Previsión Social (IPS)";
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
                if ($('#action_creado_con_ia').length) $('#action_creado_con_ia').val('1');
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
            if (!res.success || !res.data) {
                if (typeof toastr !== 'undefined') toastr.error('No se pudo estructurar la Acción e Indicador.');
                return;
            }

            var d = res.data;
            window.lastIaAccionCompletaData = d;

            // Llenar vista previa Acción
            $('#prev_accion_smart_texto').html(d.accion_nombre || 'Acción sin definir');

            // Llenar vista previa Indicador (16 Campos)
            var ind = d.indicador || {};
            $('#prev_ind_nombre_full').text(ind.nombre || 'Indicador sin nombre');
            $('#prev_ind_codigo_full').text((ind.codigo_letras || 'IND') + '-' + (ind.codigo_numeros || '001'));
            $('#prev_ind_dim_full').text((ind.dimension || 'eficacia').toUpperCase());
            $('#prev_ind_amb_full').text((ind.ambito || 'accion_estrategica').replace('_', ' ').toUpperCase());
            $('#prev_ind_frec_full').text((ind.frecuencia || 'trimestral').toUpperCase());
            $('#prev_ind_sent_full').text((ind.sentido || 'ascendente').toUpperCase());
            $('#prev_ind_desc_full').text(ind.descripcion || 'Sin descripción');
            $('#prev_ind_vars_full').text(ind.variables || 'Variables no especificadas');
            $('#prev_ind_form_full').text(ind.formula || '(A / B) * 100');
            $('#prev_ind_um_full').text(ind.unidad_medida || '%');
            $('#prev_ind_fuente_full').text(ind.fuente || 'Sistema SIESS / IPS');
            $('#prev_ind_dep_full').text(ind.dependencia_responsable || 'Dirección de Planificación IPS');
            $('#prev_ind_com_full').text(ind.comentarios || 'Generado automáticamente con IA.');

            // Desplegar Modal Preview
            $('#modalPreviewAccionEIndicadorIa').modal('show');
        },
        error: function() {
            $btn.prop('disabled', false).html('<i class="fa fa-bolt mr-1"></i> Generar Acción e Indicador con IA');
            if (typeof toastr !== 'undefined') toastr.error('Ocurrió un error al conectar con la IA.');
        }
    });
}

function aplicarAccionEIndicadorIa() {
    var d = window.lastIaAccionCompletaData;
    if (!d) return;

    var $btn = $('#btnAplicarAccionEIndicadorIa');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Creando y vinculando...');

    // 1. Etiquetar Acción como Creada con IA
    if ($('#action_creado_con_ia').length) $('#action_creado_con_ia').val('1');

    // 2. Establecer texto de Acción SMART
    if (d.accion_nombre) {
        setValToEditor('actions', 'ajaxActionsModal', d.accion_nombre);
    }
    if (d.resultado_intermedio && $('#actions_resultado_intermedio').length) {
        $('#actions_resultado_intermedio').val(d.resultado_intermedio);
    }

    // 3. Crear el Indicador en DB con etiqueta creado_con_ia = 1
    var profileId = $('#actions_profile_id').val() || '{{ $profile->id ?? "" }}';
    var ind = d.indicador || {};

    var payloadInd = {
        _token: '{{ csrf_token() }}',
        nombre: ind.nombre || ('Indicador: ' + (d.accion_nombre ? d.accion_nombre.substring(0, 50) : 'Acción IPS')),
        codigo_letras: ind.codigo_letras || 'IND',
        codigo_numeros: ind.codigo_numeros || '001',
        dimension: ind.dimension || 'eficacia',
        ambito: ind.ambito || 'accion_estrategica',
        descripcion: ind.descripcion || '',
        variables: ind.variables || '',
        formula: ind.formula || '(A / B) * 100',
        unidad_medida: ind.unidad_medida || '%',
        frecuencia: ind.frecuencia || 'trimestral',
        cobertura: ind.cobertura || 'nacional',
        sentido: ind.sentido || 'ascendente',
        fuente: ind.fuente || 'Sistema SIESS / IPS',
        dependencia_responsable: ind.dependencia_responsable || 'Dirección de Planificación',
        comentarios: ind.comentarios || 'Generado automáticamente con IA.',
        creado_con_ia: 1
    };

    if (profileId) {
        $.ajax({
            url: '{{ url("pei-profiles") }}/' + profileId + '/indicadores',
            type: 'POST',
            data: payloadInd,
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Aplicar Acción e Indicador a la Ficha (Etiquetado con IA)');
                $('#modalPreviewAccionEIndicadorIa').modal('hide');

                if (res && res.indicador) {
                    var newOption = new Option('[' + (res.indicador.codigo || 'IND-001') + '] ' + res.indicador.nombre, res.indicador.id, true, true);
                    $('#action_indicador_id').append(newOption).trigger('change');
                }

                if (typeof toastr !== 'undefined') {
                    toastr.success('¡Acción Estratégica SMART e Indicador fueron creados, vinculados y etiquetados con la insignia 🤖 Creado con IA!', '¡Operación Exitosa!');
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Aplicar Acción e Indicador a la Ficha (Etiquetado con IA)');
                $('#modalPreviewAccionEIndicadorIa').modal('hide');
                if (typeof toastr !== 'undefined') toastr.warning('Se aplicó la Acción SMART, pero debes seleccionar el indicador manualmente.');
            }
        });
    } else {
        $btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Aplicar Acción e Indicador a la Ficha (Etiquetado con IA)');
        $('#modalPreviewAccionEIndicadorIa').modal('hide');
    }
}

window.abrirModalRemitirJuntaObjetivo = function(axiId, axiTitle, juntaId, juntaNombre) {
    $('#remitir_axi_id').val(axiId);
    $('#remitir_axi_titulo_header').text('Objetivo: ' + axiTitle);
    $('#remitir_notas_remision').val('');

    // Manejo de la Junta Consultiva Receptora
    if (juntaId && juntaId !== '') {
        // Junta preconfigurada en el objetivo: usar hidden input, deshabilitar el select
        $('#remitir_junta_id_hidden').val(juntaId);
        $('#remitir_junta_id').prop('disabled', true);
        $('#bloque_junta_selector').addClass('d-none');
        $('#bloque_junta_asignada_info').removeClass('d-none');
        $('#remitir_junta_nombre_badge').text(juntaNombre || 'Junta Consultiva Vinculada al Objetivo');
    } else {
        // Sin junta preconfigurada: mostrar selector
        $('#remitir_junta_id_hidden').val('');
        $('#remitir_junta_id').prop('disabled', false).val('');
        $('#bloque_junta_selector').removeClass('d-none');
        $('#bloque_junta_asignada_info').addClass('d-none');
        if ($.fn.select2) {
            if (!$('#remitir_junta_id').hasClass('select2-hidden-accessible')) {
                $('#remitir_junta_id').select2({
                    dropdownParent: $('#modalRemitirJuntaObjetivo'),
                    width: '100%',
                    placeholder: '— Seleccionar Junta Consultiva —',
                    allowClear: true
                });
            }
        }
    }

    const $axiBlock = $('#axi-' + axiId);
    const $actionsBlocks = $axiBlock.find('[id^="actionsBlock_"]');

    let htmlAcciones = '';
    let countRed = 0;

    if ($actionsBlocks.length === 0) {
        htmlAcciones = '<div class="alert alert-warning py-2 small mb-0"><i class="fa fa-exclamation-circle mr-1"></i> Este objetivo estratégico no posee acciones registradas aún.</div>';
    } else {
        $actionsBlocks.each(function() {
            const rawId = $(this).attr('id').replace('actionsBlock_', '');
            const actionText = $(this).find('.font-weight-bold.text-dark').text().trim() || 'Acción Estratégica';
            const isRed = $(this).find('.badge-danger').length > 0 || $(this).html().indexOf('ROJO') !== -1 || $(this).html().indexOf('rojo') !== -1;

            if (isRed) {
                countRed++;
                htmlAcciones += `
                    <div class="card border border-danger p-3 bg-white mb-2 shadow-xs" style="border-radius:10px; border-left: 5px solid #dc2626 !important;">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="custom-control-input chk-accion-remitir" name="accion_ids[]" value="${rawId}" id="chk_acc_${rawId}" checked>
                            <label class="custom-control-label font-weight-bold text-dark w-100 cursor-pointer d-flex align-items-center justify-content-between mb-0" for="chk_acc_${rawId}">
                                <span>${actionText}</span>
                                <span class="badge badge-danger ml-2 px-2 py-1"><i class="fa fa-exclamation-triangle mr-1"></i> ALERTA ROJA</span>
                            </label>
                        </div>
                    </div>
                `;
            } else {
                // Acciones normales u opcionales
                htmlAcciones += `
                    <div class="card border p-3 bg-light mb-2 opacity-9" style="border-radius:10px;">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="custom-control-input chk-accion-remitir" name="accion_ids[]" value="${rawId}" id="chk_acc_${rawId}">
                            <label class="custom-control-label font-weight-bold text-secondary w-100 cursor-pointer d-flex align-items-center justify-content-between mb-0" for="chk_acc_${rawId}">
                                <span>${actionText}</span>
                                <span class="badge badge-secondary ml-2 px-2 py-1">EN CURSO</span>
                            </label>
                        </div>
                    </div>
                `;
            }
        });

        if (countRed > 0) {
            htmlAcciones = `<div class="alert alert-danger py-2 small mb-3 font-weight-bold" style="border-radius:8px;">
                <i class="fa fa-fire mr-1"></i> Se han detectado ${countRed} Acción(es) con Reportes en ALERTA ROJA preseleccionadas para remitir.
            </div>` + htmlAcciones;
        }
    }

    $('#contenedorAccionesRemitir').html(htmlAcciones);
    $('#modalRemitirJuntaObjetivo').modal('show');
};

window.enviarRemisionJuntaObjetivo = function(e) {
    e.preventDefault();
    const selectedAccions = $('.chk-accion-remitir:checked').map(function() { return $(this).val(); }).get();
    if (selectedAccions.length === 0) {
        toastr.warning('Por favor seleccioná al menos una Acción Estratégica para remitir.');
        return;
    }
    // La junta se puede enviar o dejar vacía — el servidor la autodetecta desde las acciones o el objetivo

    const $btn = $('#btnSubmitRemitirObjetivo');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Remitiendo...');

    $.ajax({
        url: '{{ route("admin.juntas.remitirAlerta") }}',
        type: 'POST',
        data: $('#formRemitirJuntaObjetivo').serialize(),
        success: function(res) {
            $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Remitir Expediente(s) a la Junta');
            if (res.success) {
                $('#modalRemitirJuntaObjetivo').modal('hide');
                mostrarModalQrJunta(res);
            } else {
                Swal.fire('Error', res.message || 'No se pudieron remitir las acciones.', 'error');
            }
        },
        error: function(err) {
            $btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Remitir Expediente(s) a la Junta');
            var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Ocurrió un error al procesar la remisión.';
            Swal.fire('Error', msg, 'error');
        }
    });
};

function mostrarModalQrJunta(data) {
    var url = data.url_intervenciones || "{{ route('admin.juntas.intervenciones') }}";
    var codigos = data.codigos ? (Array.isArray(data.codigos) ? data.codigos.join(', ') : data.codigos) : 'EXPEDIENTE';
    var juntaNombre = data.junta_nombre || 'Junta Consultiva Institucional';

    $('#qr_modal_junta_nombre').text(juntaNombre);
    $('#qr_codigo_expediente').text(codigos);
    $('#qr_input_url_junta').val(url);
    $('#qr_btn_abrir_intervenciones').attr('href', url);

    var qrSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=5&data=' + encodeURIComponent(url);
    $('#qr_img_junta').attr('src', qrSrc);

    $('#modalQrJunta').modal('show');
}

function copiarUrlJuntaQr() {
    var input = document.getElementById('qr_input_url_junta');
    input.select();
    document.execCommand('copy');
    if (window.toastr) toastr.success('Enlace copiado al portapapeles.');
}

function compartirJuntaWhatsApp() {
    var url = $('#qr_input_url_junta').val();
    var cod = $('#qr_codigo_expediente').text();
    var texto = '🏛️ *EXPEDIENTE PARA JUNTA CONSULTIVA PEI*\n\n' +
                '📌 *Expediente:* ' + cod + '\n' +
                '🔗 *Acceder al Expediente:* ' + url;
    window.open('https://api.whatsapp.com/send?text=' + encodeURIComponent(texto), '_blank');
}
</script>

{{-- Modal Remitir Acciones a Junta desde Nivel de Objetivo Estratégico --}}
<div class="modal fade" id="modalRemitirJuntaObjetivo" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);">
                <div>
                    <h5 class="modal-title font-weight-bold mb-0 text-white" style="font-size: 1.15rem;">
                        <i class="fa fa-landmark text-warning mr-2"></i> Remitir Acciones al Consejo de Sabios / Junta Consultiva
                    </h5>
                    <small class="text-white-50" id="remitir_axi_titulo_header">Objetivo Estratégico</small>
                </div>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form id="formRemitirJuntaObjetivo" onsubmit="enviarRemisionJuntaObjetivo(event)">
                @csrf
                <input type="hidden" id="remitir_axi_id" name="axi_id">
                {{-- Hidden input para cuando hay junta preconfigurada en el objetivo --}}
                <input type="hidden" id="remitir_junta_id_hidden" name="junta_id_preconfig">

                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    {{-- Banner Informativo de la Junta Asignada --}}
                    <div id="bloque_junta_asignada_info" class="p-3 mb-3 rounded shadow-xs text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 100%); border-radius: 12px;">
                        <div>
                            <small class="text-white-50 d-block text-uppercase" style="font-size:0.7rem; letter-spacing:0.05em;">Junta Consultiva Receptora (Configurada en el Objetivo)</small>
                            <span class="font-weight-bold text-warning" id="remitir_junta_nombre_badge" style="font-size:0.95rem;">Junta Consultiva</span>
                        </div>
                        <span class="badge badge-warning text-dark font-weight-bold px-3 py-1" style="border-radius: 20px;"><i class="fa fa-shield-alt mr-1"></i> Asignación Directa</span>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mb-3" style="border-radius: 10px; background-color: #eff6ff; color: #1e40af;">
                        <i class="fa fa-info-circle mr-1"></i>
                        Las **Acciones Estratégicas con Reportes en Alerta Roja** han sido identificadas y seleccionadas automáticamente para su remisión y dictamen técnico.
                    </div>

                    {{-- Lista de Acciones del Objetivo Estratégico --}}
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark small text-uppercase mb-2 d-block">
                            <i class="fa fa-tasks text-primary mr-1"></i> Acciones a Remitir:
                        </label>
                        <div id="contenedorAccionesRemitir" class="d-flex flex-column">
                            {{-- Se puebla dinámicamente vía JS --}}
                        </div>
                    </div>

                    {{-- Selector de Junta Consultiva (Opcional si el Objetivo no tiene Junta pre-configurada) --}}
                    <div id="bloque_junta_selector" class="mb-3 d-none">
                        <div class="alert alert-warning border-0 py-2 mb-2" style="border-radius:8px; background:#fffbeb; color:#92400e; font-size:0.82rem;">
                            <i class="fa fa-info-circle mr-1"></i>
                            <strong>Sin Junta configurada en el Objetivo.</strong> El sistema usará la Junta vinculada a cada Acción, o puede seleccionar una aquí:
                        </div>
                        <label class="font-weight-bold text-dark small mb-1">Junta Consultiva Receptora <span class="text-muted">(opcional)</span></label>
                        @php
                            $juntasActivas = \App\Models\Planificacion\Junta::where('activo', true)->orderBy('nombre')->get();
                        @endphp
                        <select id="remitir_junta_id" name="junta_id" class="form-control font-weight-bold select2-remitir-junta">
                            <option value="">— Autodetectar desde las Acciones —</option>
                            @foreach($juntasActivas as $jta)
                                <option value="{{ $jta->id }}">{{ $jta->nombre }} ({{ strtoupper($jta->programa) }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nivel de Prioridad --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small mb-1">Nivel de Prioridad <span class="text-danger">*</span></label>
                        <select name="prioridad" id="remitir_prioridad" class="form-control font-weight-bold">
                            <option value="ALTA" selected>🔴 ALTA - Dictamen Requerido</option>
                            <option value="EMERGENCIA">🚨 EMERGENCIA - Intervención Inmediata</option>
                            <option value="MEDIA">🟡 MEDIA - Seguimiento Preventivo</option>
                        </select>
                    </div>

                    {{-- Observaciones / Notas de Remisión --}}
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small mb-1">Observaciones / Notas de Remisión para los Consultores</label>
                        <textarea name="notas_remision" id="remitir_notas_remision" class="form-control" rows="3" placeholder="Describir las razones de la remisión, desvíos detectados o puntos críticos a dictaminar por la Junta..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-white border-top px-4 py-3">
                    <button type="button" class="btn btn-light btn-round px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark font-weight-bold btn-round shadow-sm px-4" id="btnSubmitRemitirObjetivo">
                        <i class="fa fa-paper-plane mr-1"></i> Remitir Expediente(s) a la Junta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Preview de Acción + Indicador IA --}}
<div class="modal fade" id="modalPreviewAccionEIndicadorIa" tabindex="-1" role="dialog" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius:15px; overflow:hidden;">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 100%);">
                <div>
                    <h5 class="modal-title font-weight-bold mb-0 text-warning" style="font-size: 1.15rem;">
                        <i class="fa fa-robot mr-2"></i> Vista Preliminar: Acción Estratégica & Ficha de Indicador (IA Llama 3.3 70B)
                    </h5>
                    <small class="text-white-50">Propuesta estructurada automáticamente con etiquetado de Inteligencia Artificial para la gestión del IPS.</small>
                </div>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4" style="background-color: #f8fafc; max-height:78vh; overflow-y:auto;">

                {{-- 1. Tarjeta de Acción SMART --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
                    <div class="card-header bg-white font-weight-bold text-dark d-flex align-items-center justify-content-between pt-3 pb-2 border-bottom-0">
                        <span style="font-size: 1.05rem;"><i class="fa fa-bullseye text-warning mr-2"></i> 🎯 Acción Estratégica SMART Propuesta</span>
                        <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold" style="border-radius: 20px;"><i class="fa fa-robot mr-1"></i> Creado con IA</span>
                    </div>
                    <div class="card-body pt-1 pb-3">
                        <div id="prev_accion_smart_texto" class="p-3 bg-light rounded text-dark font-weight-500" style="font-size: 0.95rem; border: 1px solid #e2e8f0; line-height: 1.6;"></div>
                    </div>
                </div>

                {{-- 2. Tarjeta Ficha de Indicador (16 Campos) --}}
                <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #3b82f6 !important;">
                    <div class="card-header bg-white font-weight-bold text-dark d-flex align-items-center justify-content-between pt-3 pb-2 border-bottom-0">
                        <span style="font-size: 1.05rem;"><i class="fa fa-ruler-combined text-primary mr-2"></i> 📊 Ficha Técnica del Indicador Asociado</span>
                        <span class="badge badge-info px-3 py-1 font-weight-bold" style="border-radius: 20px;"><i class="fa fa-check-circle mr-1"></i> Ficha Completa (16 Campos)</span>
                    </div>
                    <div class="card-body pt-1">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Nombre del Indicador</label>
                                <div id="prev_ind_nombre_full" class="font-weight-bold text-dark" style="font-size:1rem;"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Código Sugerido</label>
                                <span id="prev_ind_codigo_full" class="badge badge-dark px-3 py-2 font-weight-bold" style="font-size:0.85rem;"></span>
                            </div>

                            <div class="col-md-3 col-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Dimensión</label>
                                <span id="prev_ind_dim_full" class="badge badge-primary px-3 py-2 font-weight-bold d-block text-center" style="font-size:0.85rem;"></span>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Ámbito</label>
                                <span id="prev_ind_amb_full" class="badge badge-secondary px-3 py-2 font-weight-bold d-block text-center" style="font-size:0.85rem;"></span>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Frecuencia</label>
                                <span id="prev_ind_frec_full" class="badge badge-info px-3 py-2 font-weight-bold d-block text-center" style="font-size:0.85rem;"></span>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Sentido Deseado</label>
                                <span id="prev_ind_sent_full" class="badge badge-success px-3 py-2 font-weight-bold d-block text-center" style="font-size:0.85rem;"></span>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Descripción Técnica del Indicador</label>
                                <div id="prev_ind_desc_full" class="p-2.5 bg-white rounded border text-dark" style="font-size:0.9rem;"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Variables de la Fórmula</label>
                                <div id="prev_ind_vars_full" class="p-2.5 bg-white rounded border text-dark font-mono" style="font-family:monospace; font-size:0.88rem;"></div>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Fórmula de Cálculo</label>
                                <div id="prev_ind_form_full" class="p-2.5 bg-light rounded border text-primary font-weight-bold font-mono" style="font-family:monospace; font-size:0.92rem;"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Unidad de Medida</label>
                                <div id="prev_ind_um_full" class="p-2.5 bg-light rounded border text-dark font-weight-bold" style="font-size:0.9rem;"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Fuente de Datos</label>
                                <div id="prev_ind_fuente_full" class="text-dark font-weight-500"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Dependencia Responsable</label>
                                <div id="prev_ind_dep_full" class="text-dark font-weight-bold"></div>
                            </div>

                            <div class="col-12">
                                <label class="text-muted small font-weight-bold text-uppercase d-block mb-1">Comentarios / Observaciones</label>
                                <div id="prev_ind_com_full" class="text-muted small font-italic"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-white border-top">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Descartar</button>
                <button type="button" class="btn btn-success font-weight-bold rounded-pill px-4 shadow-sm" id="btnAplicarAccionEIndicadorIa" onclick="aplicarAccionEIndicadorIa();">
                    <i class="fa fa-check mr-1"></i> Aplicar Acción e Indicador a la Ficha (Etiquetado con IA)
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal QR Code & Enlace Seguro para la Junta Consultiva --}}
<div class="modal fade" id="modalQrJunta" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100060;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 580px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa fa-qrcode fa-2x text-warning mr-2"></i>
                    <div>
                        <span class="badge badge-warning text-dark font-weight-bold px-2 py-0.5" style="border-radius: 6px; font-size: 0.68rem;">EXPEDIENTE REMITIDO</span>
                        <h5 class="modal-title font-weight-bold text-white mb-0" id="qr_modal_junta_nombre">Junta Consultiva</h5>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4 bg-light text-center">
                <div class="alert alert-success border-0 py-2.5 px-3 mb-3 small font-weight-bold" style="border-radius: 10px; background: #f0fdf4; color: #15803d; border-left: 4px solid #22c55e !important;">
                    <i class="fa fa-check-circle mr-1"></i> Remisión registrada correctamente. Escaneá este código QR con cualquier celular o tablet para abrir el expediente.
                </div>

                {{-- QR Container --}}
                <div class="p-3 bg-white rounded-circle d-inline-block shadow-sm mb-3" style="border: 4px solid #e2e8f0;">
                    <img id="qr_img_junta" src="" alt="Código QR de Remisión a Junta" style="width: 210px; height: 210px; border-radius: 12px;" />
                </div>

                <div class="mb-3">
                    <span class="badge badge-dark font-mono font-weight-bold px-3 py-1 text-warning" id="qr_codigo_expediente" style="font-size: 0.88rem; font-family: monospace;">EXP-0000</span>
                </div>

                <div class="form-group mb-3 text-left">
                    <label class="font-weight-bold text-dark small mb-1">
                        <i class="fa fa-link text-primary mr-1"></i> Enlace Web Seguro para la Junta:
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="qr_input_url_junta" class="form-control font-weight-bold text-primary font-mono" readonly style="font-family: monospace; font-size: 0.8rem; border-radius: 8px 0 0 8px;">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary font-weight-bold px-3" onclick="copiarUrlJuntaQr()" style="border-radius: 0 8px 8px 0;">
                                <i class="fa fa-copy mr-1"></i> Copiar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap" style="gap:8px;">
                    <button type="button" class="btn btn-sm btn-success font-weight-bold rounded-pill px-3 py-2 shadow-sm" onclick="compartirJuntaWhatsApp()">
                        <i class="fab fa-whatsapp mr-1" style="font-size: 1.05rem;"></i> Compartir por WhatsApp
                    </button>
                    <a id="qr_btn_abrir_intervenciones" href="#" target="_blank" class="btn btn-sm btn-dark font-weight-bold rounded-pill px-3 py-2 shadow-sm">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Bandeja de Junta
                    </a>
                </div>
            </div>
            <div class="modal-footer bg-white py-2.5 px-4 justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 font-weight-bold" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
