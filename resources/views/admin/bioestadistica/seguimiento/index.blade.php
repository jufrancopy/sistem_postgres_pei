@extends('layouts.master')
@section('title', 'Seguimiento de datos — Bioestadística')

@section('content')
@include('admin.bioestadistica._siplan-styles')
@include('admin.bioestadistica._breadcrumbs', ['items' => [['label' => 'Seguimiento de datos']]])
<style>
    .bio-seguimiento-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .bio-seguimiento-tabs .bio-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #334155 !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        text-decoration: none !important;
        box-shadow: none;
    }
    .bio-seguimiento-tabs .bio-tab-btn:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
        color: #0f172a !important;
    }
    .bio-seguimiento-tabs .bio-tab-btn.active {
        background: #0288d1;
        border-color: #0288d1;
        color: #fff !important;
    }
    .bio-seguimiento-tabs .bio-tab-btn .material-icons {
        font-size: 18px;
        line-height: 1;
    }
</style>

<div class="card bio-siplan">
    <div class="card-header card-header-warning">
        <h4 class="card-title"><i class="material-icons">track_changes</i> Seguimiento de datos</h4>
        <p class="card-category">Actividad de usuarios y establecimientos sin reportar por período estadístico.</p>
    </div>
    <div class="card-body">
        <div class="bio-seguimiento-tabs" role="tablist" aria-label="Vistas de seguimiento">
            <a class="bio-tab-btn {{ $tab === 'actividad' ? 'active' : '' }}"
               href="{{ route('bioestadistica.seguimiento.index', array_merge(request()->except('tab'), ['tab' => 'actividad'])) }}">
                <i class="material-icons">group</i> Actividad de usuarios
            </a>
            <a class="bio-tab-btn {{ $tab === 'pendientes' ? 'active' : '' }}"
               href="{{ route('bioestadistica.seguimiento.index', array_merge(request()->except('tab'), ['tab' => 'pendientes'])) }}">
                <i class="material-icons">report_problem</i> Sin reportar
            </a>
        </div>

        <form method="GET" action="{{ route('bioestadistica.seguimiento.index') }}" class="bio-filters mb-3" id="formSeguimiento">
            <input type="hidden" name="tab" id="seguimientoTab" value="{{ $tab }}">
            <div class="form-row align-items-end">
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Año</label>
                    <input class="form-control" name="periodo_anio" type="number" min="1990" max="2100" value="{{ $filters['periodo_anio'] }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Mes</label>
                    <select class="form-control bio-select2" name="periodo_mes">
                        @foreach($months as $number => $month)
                            <option value="{{ $number }}" @selected($filters['periodo_mes'] == $number)>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Formularios SP</label>
                    <select class="form-control bio-select2" name="formulario_ids[]" multiple data-placeholder="Todos los activos">
                        @foreach($formularios as $formulario)
                            <option value="{{ $formulario->id }}" @selected(in_array($formulario->id, $filters['formulario_ids'], true))>
                                {{ $formulario->codigo }} — {{ $formulario->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Departamento</label>
                    <select class="form-control bio-select2" name="departamento_id" data-placeholder="Todos">
                        <option value="">Todos</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}" @selected($filters['departamento_id'] == $departamento->id)>
                                {{ $departamento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Distrito</label>
                    <select class="form-control bio-select2" name="distrito_id" data-placeholder="Todos">
                        <option value="">Todos</option>
                        @foreach($distritos as $distrito)
                            <option value="{{ $distrito->id }}" @selected($filters['distrito_id'] == $distrito->id)>
                                {{ $distrito->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small text-muted mb-1">Establecimiento</label>
                    <select class="form-control bio-select2" name="establecimiento_id" data-placeholder="Todos">
                        <option value="">Todos</option>
                        @foreach($establecimientos as $establecimiento)
                            <option value="{{ $establecimiento->id }}" @selected($filters['establecimiento_id'] == $establecimiento->id)>
                                {{ $establecimiento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($tab === 'actividad')
                <div class="col-md-2 mb-2">
                    <label class="small text-muted mb-1">Estado</label>
                    <select class="form-control bio-select2" name="estado" data-placeholder="Todos">
                        <option value="">Todos</option>
                        @foreach($estados as $code => $label)
                            <option value="{{ $code }}" @selected($filters['estado'] === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2 mb-2">
                    <button class="btn btn-primary btn-sm btn-block" type="submit">Consultar</button>
                </div>
                @can('bio.report.export')
                <div class="col-md-4 mb-2">
                    <label class="small text-muted mb-1 d-block">Exportar</label>
                    <div class="btn-group btn-group-sm d-flex" role="group">
                        <a class="btn btn-outline-secondary"
                           href="{{ route('bioestadistica.seguimiento.export.csv', request()->query()) }}">CSV</a>
                        <a class="btn btn-outline-success"
                           href="{{ route('bioestadistica.seguimiento.export.xlsx', request()->query()) }}">Excel</a>
                        <a class="btn btn-outline-danger"
                           href="{{ route('bioestadistica.seguimiento.export.pdf', request()->query()) }}">PDF</a>
                    </div>
                </div>
                @endcan
            </div>
        </form>

        @if($tab === 'actividad')
            @if($actividad->isEmpty())
                <div class="alert alert-light border mb-0">No hay registros para los filtros seleccionados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="25">
                        <thead class="thead-light">
                            <tr>
                                <th>Establecimiento</th>
                                <th>SP</th>
                                <th>Período</th>
                                <th>Estado</th>
                                <th>Creó</th>
                                <th>Último editó</th>
                                <th>Envió</th>
                                <th>Aprobó</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($actividad as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row['establecimiento'] }}</strong>
                                        <div class="small text-muted">{{ $row['departamento'] }} / {{ $row['distrito'] }}</div>
                                    </td>
                                    <td data-order="{{ $row['formulario'] }}">
                                        <span class="badge badge-info">{{ $row['formulario'] }}</span>
                                        <div class="small text-muted">{{ $row['formulario_nombre'] }}</div>
                                    </td>
                                    <td>{{ $row['periodo'] }}</td>
                                    <td data-order="{{ $row['estado'] }}">
                                        <span class="badge {{ \App\Models\Bioestadistica\Record::estadoBadge($row['estado']) }}">
                                            {{ $row['estado_label'] }}
                                        </span>
                                    </td>
                                    <td>{{ $row['created_by'] }}</td>
                                    <td>{{ $row['updated_by'] }}</td>
                                    <td>{{ $row['submitted_by'] }}</td>
                                    <td>{{ $row['approved_by'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="small text-muted mt-2 mb-0">Máximo 5000 filas por consulta.</p>
            @endif
        @else
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted text-uppercase">Pendientes</div>
                        <div class="h4 mb-0 font-weight-bold">{{ $summary['pendientes'] }}</div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted text-uppercase">Sin abrir</div>
                        <div class="h4 mb-0 font-weight-bold text-danger">{{ $summary['sin_abrir'] }}</div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted text-uppercase">Sin enviar</div>
                        <div class="h4 mb-0 font-weight-bold text-warning">{{ $summary['sin_enviar'] }}</div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted text-uppercase">Al día</div>
                        <div class="h4 mb-0 font-weight-bold text-success">{{ $summary['al_dia'] }}</div>
                    </div>
                </div>
            </div>

            @if($pendientes->isEmpty())
                <div class="alert alert-light border mb-0">No hay pendientes para los filtros seleccionados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm bio-data-table" data-page-length="25">
                        <thead class="thead-light">
                            <tr>
                                <th>Establecimiento</th>
                                <th>SP</th>
                                <th>Situación</th>
                                <th>Estado</th>
                                <th>Último actor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendientes as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row['establecimiento'] }}</strong>
                                        <div class="small text-muted">{{ $row['departamento'] }} / {{ $row['distrito'] }}</div>
                                    </td>
                                    <td data-order="{{ $row['formulario'] }}">
                                        <span class="badge badge-info">{{ $row['formulario'] }}</span>
                                        <div class="small text-muted">{{ $row['formulario_nombre'] }}</div>
                                    </td>
                                    <td data-order="{{ $row['situacion'] }}">
                                        @if($row['situacion'] === 'sin_abrir')
                                            <span class="badge badge-danger">{{ $row['situacion_label'] }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $row['situacion_label'] }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $row['estado_label'] }}</td>
                                    <td>{{ $row['ultimo_actor'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
@include('admin.bioestadistica._siplan-scripts')
@endsection
