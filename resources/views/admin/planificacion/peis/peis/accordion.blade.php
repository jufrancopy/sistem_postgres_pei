<div class="container">
    <div class="row contentMain">
        @foreach ($profile->children->sortBy('order_item') as $axi)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header bg-light" id="headingAxi_{{ $axi->id }}">
                        <h5 class="mb-0" id="axisBlock_{{ $axi->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-link text-left flex-grow-1" type="button"
                                    data-toggle="collapse" data-target="#{{ $axi->id }}"
                                    aria-expanded="false" aria-controls="{{ $axi->id }}"
                                    style="max-height: 100px; overflow-y: auto; white-space: pre-line; font-weight: bold;">
                                    <span class="badge badge-secondary mr-1">{{ $niveles['axi'] ?? 'Nivel 1' }}</span>
                                    {!! $axi->name !!}
                                </button>
                                {{-- Indicador de estrategias FODA vinculadas --}}
                                @php $countEstrategias = $axi->strategies->count(); @endphp
                                @if($countEstrategias > 0)
                                    <a class="btn btn-warning btn-circle mr-1" data-id="{{ $axi->id }}"
                                        href="javascript:void(0)" id="showStrategies" title="Ver estrategias FODA vinculadas">
                                        <i class="fa fa-chess"></i>
                                        <span class="badge badge-light">{{ $countEstrategias }}</span>
                                    </a>
                                @else
                                    <a class="btn btn-outline-warning btn-circle mr-1" data-id="{{ $axi->id }}"
                                        href="javascript:void(0)" id="showStrategies" title="Sin estrategias FODA vinculadas">
                                        <i class="fa fa-chess"></i>
                                    </a>
                                @endif
                                <a class="btn btn-info btn-circle mr-1" data-id="{{ $axi->id }}" data-type="edit"
                                    href="javascript:void(0)" id="createAxis" title="Editar Estrategia">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-success btn-circle mr-1 createGoalsButton" data-id="{{ $axi->id }}"
                                    data-type="create" href="javascript:void(0)" id="createGoals"
                                    title="Agregar {{ $niveles['goal'] ?? 'Nivel 2' }}">
                                    <i class="fa-solid fa-circle-plus fa-beat"></i>
                                </a>
                                <a class="btn btn-danger btn-circle deleteItem" data-id="{{ $axi->id }}"
                                    href="javascript:void(0)" id="deleteProfile" title="Eliminar {{ $niveles['axi'] ?? 'Nivel 1' }}">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
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
                                            <div class="d-flex justify-content-between align-items-center">
                                                <button class="btn btn-link text-left flex-grow-1" type="button"
                                                    data-toggle="collapse" data-target="#{{ $goal->id }}"
                                                    aria-expanded="false" aria-controls="{{ $goal->id }}"
                                                    style="max-height: 100px; overflow-y: auto; white-space: pre-line; font-weight: bold;">
                                                    <span class="badge badge-primary mr-1">{{ $niveles['goal'] ?? 'Nivel 2' }}</span>
                                                    {!! $goal->name !!}
                                                </button>
                                                <a class="btn btn-info btn-circle mr-1" data-id="{{ $goal->id }}"
                                                    data-type="edit" href="javascript:void(0)" id="createGoals"
                                                    title="Editar {{ $niveles['goal'] ?? 'Nivel 2' }}">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-success btn-circle mr-1 createActionsButton"
                                                    data-id="{{ $goal->id }}" data-type="create"
                                                    href="javascript:void(0)" id="createActions"
                                                    title="Agregar {{ $niveles['action'] ?? 'Acción' }}">
                                                    <i class="fa-solid fa-circle-plus fa-beat"></i>
                                                </a>
                                                <a class="btn btn-danger btn-circle deleteItem"
                                                    data-id="{{ $goal->id }}" href="javascript:void(0)"
                                                    id="deleteProfile" title="Eliminar {{ $niveles['goal'] ?? 'Nivel 2' }}">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
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
                                                    <h6 class="mb-0"><i class="fa fa-exclamation-triangle mr-1"></i> Sin acciones registradas</h6>
                                                </div>
                                            @else
                                                <div class="card-header bg-info text-white">
                                                    <h6 class="mb-0"><i class="fa fa-rocket mr-1"></i> Acciones del Plan</h6>
                                                </div>
                                            @endif

                                            <div class="actionDetail">
                                                <div id="actionDetail">
                                                    @foreach ($goal->children->sortBy('order_item') as $action)
                                                        <div class="card mt-2">
                                                            <div class="card-header" id="headingAction">
                                                                <small class="text-muted">
                                                                    @if ($action->analyst == null)
                                                                        <i class="fa fa-user mr-1"></i> Analista: <em>Pendiente</em>
                                                                    @else
                                                                        <i class="fa fa-user mr-1"></i> Analista:
                                                                        <span class="badge badge-light">{{ $action->analyst->name }}</span>
                                                                    @endif
                                                                </small>
                                                                <div class="card-body p-2" id="actionsBlock_{{ $action->id }}">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-sm mb-0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Nro.</th>
                                                                                    <th>Acción</th>
                                                                                    <th>Indicador</th>
                                                                                    <th>Línea de Base</th>
                                                                                    <th>Meta</th>
                                                                                    <th>Responsable</th>
                                                                                    <th>Opciones</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="actionFile_{{ $action->id }}">
                                                                                <tr>
                                                                                    <td>{{ $action->order_item }}</td>
                                                                                    <td>{!! $action->name !!}</td>
                                                                                    <td>{{ $action->indicator }}</td>
                                                                                    <td>{{ $action->baseline }}</td>
                                                                                    <td>{{ $action->target }}</td>
                                                                                    <td>
                                                                                        @foreach ($action->responsibles as $responsible)
                                                                                            <span class="badge badge-secondary">{{ $responsible->dependency }}</span>
                                                                                        @endforeach
                                                                                    </td>
                                                                                    <td>
                                                                                        <a class="btn btn-info btn-circle btn-sm"
                                                                                            data-id="{{ $action->id }}"
                                                                                            data-type="edit"
                                                                                            href="javascript:void(0)"
                                                                                            id="createActions"
                                                                                            title="Editar Acción">
                                                                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                                                                        </a>
                                                                                        <a class="btn btn-warning btn-circle btn-sm reportProgress"
                                                                                            data-id="{{ $action->id }}"
                                                                                            href="javascript:void(0)"
                                                                                            id="reportProgress"
                                                                                            title="Reportar Avance">
                                                                                            <i class="fas fa-chart-line"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
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
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
