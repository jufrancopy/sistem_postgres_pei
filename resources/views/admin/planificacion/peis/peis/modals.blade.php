{{-- Inicio Modales --}}
<div class="modal fade" id="ajaxMisionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingMision"></h4>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnMision" value="create">Guardar
                            cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxVisionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingVision"></h4>
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
                    {{ Form::hidden('level', 'missionary', ['id' => 'vision_level']) }}
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar
                        </button>
                        <button type="submit" class="btn btn-success" id="saveBtnVision" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxValuesModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingValues"></h4>
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
                    {{ Form::hidden('type', 'institucional', ['id' => 'values_type']) }}
                    {{ Form::hidden('level', null, ['id' => 'values_level']) }}
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnValues" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxAxisModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingAxis"></h4>
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


                    <div class="axis mb-2">
                        {{ Form::label('name', 'Eje:', ['class' => 'control-label']) }}
                        {{ Form::textarea('name', null, [
                            'class' => 'form-control editor',
                            'id' => 'axis',
                        ]) }}
                    </div>

                    <div class="order mb-2">
                        {{ Form::label('order_item', 'Orden:', ['class' => 'control-label']) }}
                        {{ Form::number('order_item', null, [
                            'class' => 'form-control',
                            'id' => 'axis_order_item',
                        ]) }}
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnAxis" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxGoalsModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingGoals"></h4>
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

                    <div class="goal mb-2">
                        {{ Form::label('name', 'Objetivo:', ['class' => 'control-label']) }}
                        {{ Form::textarea('name', null, [
                            'class' => 'form-control editor',
                            'id' => 'goals',
                        ]) }}
                    </div>

                    <div class="goals_order_item mb-2">
                        {{ Form::label('goals_order_item', 'Orden:', ['class' => 'control-label']) }}
                        {{ Form::number('order_item', null, [
                            'class' => 'form-control',
                            'id' => 'goals_order_item',
                        ]) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('strategies', 'Estrategias del Cruce de Ambientes (Análsis FODA):') }}
                        {!! Form::select('strategy_id[]', [], null, [
                            'id' => 'strategies',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnGoals" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxStrategiesModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingStrategies"></h4>
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
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxActionsModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingActions"></h4>
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

                    <div class="actions mb-2">
                        {{ Form::label('name', 'Describa la acción Acción:', ['class' => 'control-label']) }}
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
                    <div class="form-group indicator">
                        {{ Form::label('indicator', 'Indicador:', ['class' => 'control-label']) }}
                        {{ Form::text('indicator', null, ['class' => 'form-control', 'id' => 'actions_indicator']) }}
                    </div>

                    <div class="form-group baseline">
                        {{ Form::label('baseline', 'Linea de Base:', ['class' => 'control-label']) }}
                        {{ Form::text('baseline', null, ['class' => 'form-control', 'id' => 'actions_baseline']) }}
                    </div>

                    <div class="form-group target">
                        {{ Form::label('target', 'Meta:', ['class' => 'control-label']) }}
                        {{ Form::text('target', null, ['class' => 'form-control', 'id' => 'actions_target']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('responsibles', 'Asignar Responsables:') }}
                        {!! Form::select('responsible_id[]', [], null, [
                            'id' => 'responsibles',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnActions" value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxHistoricalModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingHistorical"></h4>
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

<div class="modal fade" id="ajaxAxisListlModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingAxisList"></h4>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="axisList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Nombre</th>
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
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxGoalsListModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingGoalsList"></h4>
            </div>

            <div class="modal-body">
                <div class="table-responsive" id="goalsList">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nro.</th>
                                <th>Nombre</th>
                                <th>Estrategias del Cruce de Ambientes</th>
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
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxActionsListModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalHeadingActionsList"></h4>
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
                        <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxDefineCriteriaModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="card-header card-header-info">
                <h4 class="modal-title" id="modalReportProgress"></h4>
            </div>

            <div class="modal-body">
                <form id="monitoringType" name="monitoringType" class="form-horizontal">
                    {{ Form::hidden('profile_id', null, ['id' => 'progress_profile_id']) }}
                    {{ Form::hidden('parent_id', null, ['id' => 'progress_parent_id']) }}
                    {{ Form::hidden('group_id', null, ['id' => 'progress_group_id']) }}
                    {{ Form::hidden('mision', null, ['id' => 'progress_mision']) }}
                    {{ Form::hidden('vision', null, ['id' => 'progress_vision']) }}
                    {{ Form::hidden('type', 'institucional', ['id' => 'progress_type']) }}
                    {{ Form::hidden('level', 'action', ['id' => 'progress_level']) }}
                    {{ Form::hidden('period', null, ['class' => 'form-control', 'id' => 'progress_period']) }}
                    {{ Form::hidden('numerator', null, ['class' => 'form-control', 'id' => 'progress_numerator']) }}
                    {{ Form::hidden('operator', null, ['class' => 'form-control', 'id' => 'progress_numerator']) }}
                    {{ Form::hidden('denominator', null, ['class' => 'form-control', 'id' => 'progress_denominator']) }}
                    {{ Form::hidden('goal', null, ['class' => 'form-control', 'id' => 'progress_goal']) }}
                    {{ Form::hidden('progress', null, ['class' => 'form-control', 'id' => 'progress_progress']) }}
                    {{ Form::hidden('dependency_id', null, ['class' => 'form-control', 'id' => 'progress_dependency']) }}
                    {{ Form::hidden('order_item', null, ['class' => 'form-control', 'id' => 'progress_order_item']) }}

                    <div class="progress_action mb-2">
                        {{ Form::label('name', 'Acción:', ['class' => 'control-label']) }}
                        {{ Form::text('name', null, [
                            'class' => 'form-control',
                            'id' => 'progress_action',
                            'readonly',
                        ]) }}
                    </div>

                    <div class="progress_indicator mb-2">
                        {{ Form::label('indicator', 'Indicador:', ['class' => 'control-label']) }}
                        {{ Form::text('indicator', null, [
                            'class' => 'form-control',
                            'id' => 'progress_indicator',
                            'readonly',
                        ]) }}
                    </div>

                    <div class="progress_baseline mb-2">
                        {{ Form::label('baseline', 'Linea de Base:', ['class' => 'control-label']) }}
                        {{ Form::text('baseline', null, [
                            'class' => 'form-control',
                            'id' => 'progress_baseline',
                            'readonly',
                        ]) }}
                    </div>

                    <div class="progress_target mb-2">
                        {{ Form::label('target', 'Meta', ['class' => 'control-label']) }}
                        {{ Form::text('target', null, [
                            'class' => 'form-control',
                            'id' => 'progress_target',
                            'readonly',
                        ]) }}
                    </div>

                    {{ Form::label('responsiblesDetail', 'Responsables', ['class' => 'control-label']) }}
                    <div id="responsiblesContainer" class="mb-2"></div>
                    
                    <div class="form-group progress_responsibles">
                        {{ Form::label('progress_responsibles', 'Responsables:') }}
                        {!! Form::select('responsible_id[]', [], null, [
                            'id' => 'progress_responsibles',
                            'style' => 'width:100%',
                            'multiple',
                        ]) !!}
                    </div>

                    <div class="progress_report_type mb-2">
                        {{ Form::label('report_type', 'Tipo de Reporte:', ['class' => 'control-label']) }}
                        {{ Form::select('report_type', ['qualitative' => 'Cualitativo', 'quantitative' => 'Cuantitativo'], null, [
                            'class' => 'form-control',
                            'placeholder' => '',
                            'id' => 'progress_report_type',
                            'style' => 'width: 100%',
                        ]) }}
                    </div>

                    <div class="qualitative">
                        <div id="parameters" class="mt-4">
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <input type="text" class="form-control" id="description">
                            </div>
                            <div class="form-group">
                                <label for="value">Valor (%):</label>
                                <input type="text" class="form-control" id="value">
                            </div>
                            <div class="form-group">
                                <label for="color">Color:</label>
                                {{ Form::select(
                                    'color',
                                    [
                                        'bg-danger' => 'Rojo',
                                        'bg-warning' => 'Amarillo',
                                        'bg-success' => 'Verde',
                                        'bg-info' => 'Azul',
                                        'bg-primary' => 'Primario',
                                        'bg-secondary' => 'Secundario',
                                        'bg-dark' => 'Oscuro',
                                        'bg-light' => 'Claro',
                                    ],
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'style' => 'width:100%',
                                        'id' => 'color',
                                    ],
                                ) }}

                            </div>
                            <button type="button" class="btn btn-primary" id="insertCheckbox">Insertar
                                Parámetro</button>
                        </div>

                        <div id="checkboxContainer">

                        </div>
                    </div>

                    <div class="quantitative">
                        <div class="calculator">
                            {{-- <h3>Calculadora</h3> --}}
                            <input type="number" id="progress_denominator" class="form-control"
                                placeholder="META NUMÉRICA">
                            <input type="number" id="progress_numerator" class="form-control"
                                placeholder="Logrado">
                            {{-- <button type="button" class="btn btn-primary operation-btn" data-operation="add">Sumar (+)</button>
                            <button type="button" class="btn btn-primary operation-btn" data-operation="subtract">Restar (-)</button>
                            <button type="button" class="btn btn-primary operation-btn" data-operation="multiply">Multiplicar (x)</button>
                            <button type="button" class="btn btn-primary operation-btn" data-operation="divide">Dividir (/)</button> --}}
                            <button type="button" class="btn btn-primary operation-btn"
                                data-operation="percentage">Porcentaje (%)</button>
                        </div>

                        <div class="progress_progress mb-2">
                            {{ Form::label('progress', 'Resultado:', ['class' => 'control-label']) }}
                            {{ Form::text('progress', null, [
                                'class' => 'form-control',
                                'id' => 'progress_progress',
                                'readonly' => 'readonly',
                            ]) }}
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="saveBtnMonitoringType"
                            value="create">Guardar
                            cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Fin Modales --}}
