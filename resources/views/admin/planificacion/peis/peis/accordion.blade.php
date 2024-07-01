<div class="container">
    <div class="row contentMain">
        @foreach ($profile->children->sortBy('order_item') as $axi)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header bg-light" id="headingAxi_{{ $axi->id }}">
                        <h5 class="mb-0" id="axisBlock_{{ $axi->id }}">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                    data-target="#{{ $axi->id }}" aria-expanded="false"
                                    style="max-height: 100px; overflow-y: auto; white-space: pre-line; text-align: left; font-weight: bold;"
                                    aria-controls="{{ $axi->id }}" data-name="{{ $axi->name }}">
                                    {!! $axi->name !!}
                                </button>
                                <a class="btn btn-info btn-circle" data-id="{{ $axi->id }}" data-type="edit"
                                    href="javascript:void(0)" id="createAxis"><i class="fa fa-edit"
                                        aria-hidden="true"></i></a>
                                <a class="btn btn-success btn-circle createGoalsButton" data-id="{{ $axi->id }}"
                                    data-type="create" href="javascript:void(0)" id="createGoals"><i
                                        class="fa-solid fa-circle-plus fa-beat"></i></a>
                                <a class="btn btn-danger btn-circle deleteItem" data-id="{{ $axi->id }}"
                                    href="javascript:void(0)" id="deleteProfile"><i class="fa fa-trash"
                                        aria-hidden="true"></i>
                                </a>
                            </div>
                        </h5>
                    </div>
                    <div id="{{ $axi->id }}" class="collapse" aria-labelledby="headingAxi_{{ $axi->id }}"
                        data-parent="#accordionAxi">
                        <div class="card-body">
                            @foreach ($axi->children->sortBy('order_item') as $goal)
                                <div class="card mb-3">
                                    <div class="card-header" id="headingGoal_{{ $goal->id }}">
                                        <h5 class="mb-0">
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#{{ $goal->id }}" aria-expanded="false"
                                                    style="max-height: 100px; overflow-y: auto; white-space: pre-line; text-align: left; font-weight: bold;"
                                                    aria-controls="{{ $goal->id }}"
                                                    data-name="{{ $goal->name }}">
                                                    {!! $goal->name !!}
                                                </button>
                                                <a class="btn btn-warning btn-circle" data-id="{{ $goal->id }}"
                                                    data-type="edit" href="javascript:void(0)" id="showStrategies"><i
                                                        class="fa fa-eye" aria-hidden="true"></i></a>
                                                <a class="btn btn-info btn-circle" data-id="{{ $goal->id }}"
                                                    data-type="edit" href="javascript:void(0)" id="createGoals"><i
                                                        class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a class="btn btn-success btn-circle createActionsButton"
                                                    data-id="{{ $goal->id }}" data-type="create"
                                                    href="javascript:void(0)" id="createActions"><i
                                                        class="fa-solid fa-circle-plus fa-beat"></i></a>
                                                <a class="btn btn-danger btn-circle deleteItem"
                                                    data-id="{{ $goal->id }}" href="javascript:void(0)"
                                                    id="deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </h5>
                                    </div>
                                    <div id="{{ $goal->id }}" class="collapse accordionAction"
                                        aria-labelledby="headingGoal_{{ $goal->id }}"
                                        data-parent="#accordionGoal_{{ $goal->id }}">
                                        <div class="card-body">
                                            @if (count($goal->children) == 0)
                                                <div class="card-header bg-danger text-white">
                                                    <h3>Sin acciones</h3>
                                                </div>
                                            @else
                                                <div class="card-header bg-info text-white">
                                                    <h3>Lista de Acciones</h3>
                                                </div>
                                            @endif
                                            <div class="actionDetail">
                                                <div id="actionDetail">
                                                    @foreach ($goal->children->sortBy('order_item') as $action)
                                                        <div class="card">
                                                            <div class="card-header" id="headingAction">
                                                                <label>
                                                                    @if ($action->analyst == null)
                                                                        Analista: Pendiente
                                                                    @else
                                                                        Analista:
                                                                        <span
                                                                            class="badge badge-light">{{ $action->analyst->name }}</span>
                                                                    @endif
                                                                </label>
                                                                <h5 class="mb-0">
                                                                    <div class="card-body"
                                                                        id="actionsBlock_{{ $action->id }}">
                                                                        <div class="table-responsive">
                                                                            <table class="table table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Nro.</th>
                                                                                        <th>Acción</th>
                                                                                        <th>Indicador</th>
                                                                                        <th>Línea de Base</th>
                                                                                        <th>Meta</th>
                                                                                        <th>Responsable</th>
                                                                                        <th>Acciones</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    id="actionFile_{{ $action->id }}">
                                                                                    <tr>
                                                                                        <td>{{ $action->order_item }}
                                                                                        </td>
                                                                                        <td>{!! $action->name !!}
                                                                                        </td>
                                                                                        <td>{{ $action->indicator }}
                                                                                        </td>
                                                                                        <td>{{ $action->baseline }}
                                                                                        </td>
                                                                                        <td>{{ $action->target }}
                                                                                        </td>
                                                                                        <td>
                                                                                            @foreach ($action->responsibles as $responsible)
                                                                                                <span
                                                                                                    class="badge badge-secondary">{{ $responsible->dependency }}
                                                                                                </span>
                                                                                            @endforeach
                                                                                        </td>
                                                                                        <td>
                                                                                            <a class="btn btn-info btn-circle"
                                                                                                data-id="{{ $action->id }}"
                                                                                                data-type="edit"
                                                                                                href="javascript:void(0)"
                                                                                                id="createActions"><i
                                                                                                    class="fa fa-edit"
                                                                                                    aria-hidden="true"></i>
                                                                                            </a>
                                                                                            {{-- <a class="btn btn-danger btn-circle deleteItem"
                                                                                                data-id="{{ $action->id }}"
                                                                                                href="javascript:void(0)"
                                                                                                id="deleteProfile"><i
                                                                                                    class="fa fa-trash"
                                                                                                    aria-hidden="true"></i>
                                                                                            </a> --}}
                                                                                            <a class="btn btn-warning btn-circle reportProgress"
                                                                                                data-id="{{ $action->id }}"
                                                                                                href="javascript:void(0)"
                                                                                                id="reportProgress"><i class="fas fa-chart-line"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    @endforeach
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
        @endforeach
    </div>
</div>
