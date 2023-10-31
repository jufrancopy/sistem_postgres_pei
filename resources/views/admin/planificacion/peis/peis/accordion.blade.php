{{-- Inicio Contenido Desplegable (Acordeon) --}}
<div class="row contentMain">
    <div class="col-12">
        <div class="accordion" id="accordionAxi">
            @foreach ($profile->children as $axi)
                {{-- EJES --}}
                <div class="card bg-primary">
                    {{-- Inicio Encabezado de la tarjeta EJES --}}
                    <div class="card-header bg-light" id="headingAxi_{{ $axi->id }}">
                        <h2 class="mb-0" id="axisBlock_{{ $axi->id }}">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-link btn-block text-left collapsed" type="button"
                                    data-toggle="collapse" data-target="#{{ $axi->id }}" aria-expanded="false"
                                    aria-controls="{{ $axi->id }}">
                                    <h6>{!! $axi->name !!}</h6>
                                </button>
                                <a class="btn btn-success text-white btn-circle" data-id="{{ $axi->id }}"
                                    data-type="edit" href="javascript:void(0)" id="createAxis"> <i class="fa fa-edit"
                                        aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-info text-white btn-circle createGoalsButton"
                                    data-id="{{ $axi->id }}" data-type="create" href="javascript:void(0)"
                                    id="createGoals"> <i class="fa-solid fa-circle-plus fa-beat"></i>
                                </a>
                                <a class="btn btn-danger text-white btn-circle deleteItem" data-id="{{ $axi->id }}"
                                    href="javascript:void(0)" id="deleteProfile"> <i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                </a>
                            </div>
                        </h2>
                    </div>
                    {{-- FIn Encabezado de la tarjeta EJES --}}
                    <div id="{{ $axi->id }}"
                        class="collactions
                        aria-labelledby="headingAxi_{{ $axi->id }}"
                        data-parent="#accordionAxi">
                        <div class="row contentGoals">
                            <div class="card-body">
                                <div class="accordionGoal" id="accordionGoal_{{ $axi->id }}">
                                    <div class="container">
                                        @foreach ($axi->children as $goal)
                                            <div class="card">
                                                <div class="card-header bg-light" id="headingGoal_{{ $goal->id }}">
                                                    <h2 class="mb-0" id="goalsBlock_{{ $goal->id }}">
                                                        <div class="d-flex justify-content-between">
                                                            <button class="btn btn-link btn-block text-left collapsed"
                                                                type="button" data-toggle="collapse"
                                                                data-target="#{{ $goal->id }}"
                                                                aria-expanded="false"
                                                                aria-controls="{{ $goal->id }}">
                                                                <h6>{!! $goal->name !!}</h6>
                                                            </button>
                                                            <a class="btn btn-warning text-white btn-circle"
                                                                data-id="{{ $goal->id }}" data-type="edit"
                                                                href="javascript:void(0)" id="showStrategies"> <i
                                                                    class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                            <a class="btn btn-success text-white btn-circle"
                                                                data-id="{{ $goal->id }}" data-type="edit"
                                                                href="javascript:void(0)" id="createGoals"> <i
                                                                    class="fa fa-edit" aria-hidden="true"></i>
                                                            </a>
                                                            <a class="btn btn-info text-white btn-circle createActionsButton"
                                                                data-id="{{ $goal->id }}" data-type="create"
                                                                href="javascript:void(0)" id="createActions"> <i
                                                                    class="fa-solid fa-circle-plus fa-beat"></i>
                                                            </a>
                                                            <a class="btn btn-danger text-white btn-circle deleteItem"
                                                                data-id="{{ $goal->id }}" href="javascript:void(0)"
                                                                id="deleteProfile"> <i class="fa fa-trash"
                                                                    aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </h2>
                                                </div>
                                                <div id="{{ $goal->id }}" class="collapse"
                                                    aria-labelledby="headingGoal_{{ $goal->id }}"
                                                    data-parent="#accordionGoal_{{ $goal->id }}">
                                                </div>

                                                <div class="row contentActions">
                                                    <div class="card-body">
                                                        <div class="accordionAction"
                                                            id="accordionAction_{{ $goal->id }}">
                                                            @foreach ($goal->children as $action)
                                                                <div class="container">
                                                                    <div class="card">
                                                                        <div class="card-header bg-light"
                                                                            id="headingAction_{{ $action->id }}">

                                                                            <div class="table-responsive"
                                                                                id="actionsBlock_{{ $action->id }}">

                                                                                <table class="table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Acción
                                                                                            </th>
                                                                                            <th>Indicador
                                                                                            </th>
                                                                                            <th>Linea
                                                                                                de
                                                                                                Base
                                                                                            </th>
                                                                                            <th>Meta
                                                                                            </th>

                                                                                            <th>Responsable
                                                                                            </th>
                                                                                            <th>Acciones
                                                                                            </th>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
                                                                                                {!! $action->name !!}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $action->indicator }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $action->baseline }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $action->target }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @foreach ($action->responsibles as $responsible)
                                                                                                    <span
                                                                                                        class="badge badge-secondary">{{ $responsible->dependency }}</span>
                                                                                                @endforeach
                                                                                            </td>
                                                                                            <td>
                                                                                                <a class="btn btn-success text-white btn-circle"
                                                                                                    data-id="{{ $action->id }}"
                                                                                                    data-type="edit"
                                                                                                    href="javascript:void(0)"
                                                                                                    id="createActions">
                                                                                                    <i class="fa fa-edit"
                                                                                                        aria-hidden="true"></i>
                                                                                                </a>
                                                                                                <a class="btn btn-danger text-white btn-circle deleteItem"
                                                                                                    data-id="{{ $action->id }}"
                                                                                                    href="javascript:void(0)"
                                                                                                    id="deleteProfile">
                                                                                                    <i class="fa fa-trash"
                                                                                                        aria-hidden="true"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                    </tr>
                                                                                    </thead>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
{{-- Fin Contenido Desplegable (Acordeon) --}}
