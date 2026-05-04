@extends('layouts.master')
@section('title', $proyecto->codigo . ' — ' . $proyecto->nombre)

@section('content')

{{-- ── Header ── --}}
<div class="card mb-3">
    <div class="card-header card-header-info">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4 class="card-title mb-1">
                    <span class="badge badge-light text-dark mr-2">{{ $proyecto->codigo }}</span>
                    {{ $proyecto->nombre }}
                </h4>
                <div>
                    <span class="badge {{ \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($proyecto->estado) }} mr-2">
                        {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($proyecto->estado) }}
                    </span>
                    @if($proyecto->peiProfile)
                    <span class="badge badge-success">
                        <i class="fa fa-link mr-1"></i> Vinculado al PEI
                    </span>
                    @else
                    <span class="badge badge-warning">
                        <i class="fa fa-unlink mr-1"></i> Sin vincular al PEI
                    </span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <a href="{{ route('proyectos-institucionales.edit', $proyecto->id) }}" class="btn btn-primary btn-circle" title="Editar">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="{{ route('proyectos-institucionales.index') }}" class="btn btn-secondary btn-circle ml-1" title="Volver">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">

    {{-- ── Columna izquierda: info + checklist ── --}}
    <div class="col-md-8">

        {{-- Flujo de estados ── --}}
        <div class="card shadow mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold"><i class="fa fa-stream mr-1"></i> Flujo del Proyecto</h6>
                @if(count($transiciones) > 0)
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCambiarEstado">
                    <i class="fa fa-arrow-right mr-1"></i> Avanzar estado
                </button>
                @endif
            </div>
            <div class="card-body py-3">
                @php
                $todosEstados = array_keys(\App\Models\Proyectos\ProyectoInstitucional::ESTADOS);
                $estadoActual = $proyecto->estado;
                $estadosOrden = ['solicitud','en_analisis','en_desarrollo','en_homologacion','consulta_gerencias','en_tramite','aprobado','en_ejecucion','finalizado'];
                $posActual = array_search($estadoActual, $estadosOrden);
                @endphp
                <div class="d-flex flex-wrap align-items-center">
                    @foreach($estadosOrden as $i => $est)
                    @php
                        $pos = array_search($est, $estadosOrden);
                        $clase = $pos < $posActual ? 'bg-success text-white' : ($est === $estadoActual ? 'bg-primary text-white' : 'bg-light text-muted');
                    @endphp
                    <div class="text-center mb-2" style="min-width:90px">
                        <div class="badge {{ $clase }} d-block mb-1 py-2" style="font-size:.65rem;white-space:normal">
                            {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($est) }}
                        </div>
                    </div>
                    @if($i < count($estadosOrden)-1)
                    <i class="fa fa-chevron-right text-muted mx-1 mb-2" style="font-size:.7rem"></i>
                    @endif
                    @endforeach
                </div>
                @if(in_array($estadoActual, ['rechazado_docs','rechazado_tecnico']) && $proyecto->motivo_rechazo)
                <div class="alert alert-danger mt-2 mb-0 py-2">
                    <i class="fa fa-times-circle mr-1"></i>
                    <strong>Motivo de rechazo:</strong> {{ $proyecto->motivo_rechazo }}
                </div>
                @endif
            </div>
        </div>

        {{-- Datos generales ── --}}
        <div class="card shadow mb-3">
            <div class="card-header"><h6 class="mb-0 font-weight-bold"><i class="fa fa-info-circle mr-1"></i> Datos Generales</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm mb-0">
                            <tr><td class="text-muted">Solicitante</td><td>{{ $proyecto->dependenciaSolicitante?->dependency ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Ejecutora</td><td>{{ $proyecto->dependenciaEjecutora?->dependency ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Analista</td><td>{{ $proyecto->analista?->name ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Creado por</td><td>{{ $proyecto->creadoPor?->name ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm mb-0">
                            <tr><td class="text-muted">Fecha solicitud</td><td>{{ $proyecto->fecha_solicitud?->format('d/m/Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Fin estimado</td><td>{{ $proyecto->fecha_fin_estimada?->format('d/m/Y') ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Resolución</td><td>{{ $proyecto->nro_resolucion ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Avance</td>
                                <td>
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar bg-{{ $proyecto->avance_pct >= 100 ? 'success' : ($proyecto->avance_pct >= 50 ? 'info' : 'warning') }}"
                                             style="width:{{ $proyecto->avance_pct }}%"></div>
                                    </div>
                                    <small>{{ $proyecto->avance_pct }}%</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($proyecto->descripcion)
                <hr>
                <p class="mb-0 text-muted">{{ $proyecto->descripcion }}</p>
                @endif
            </div>
        </div>

        {{-- Vinculación PEI ── --}}
        @if($proyecto->peiProfile)
        <div class="card shadow mb-3 border-left-success">
            <div class="card-header"><h6 class="mb-0 font-weight-bold text-success"><i class="fa fa-link mr-1"></i> Vinculación al PEI</h6></div>
            <div class="card-body py-2">
                <small class="text-muted">Acción vinculada:</small>
                <div>{!! $proyecto->peiProfile->name !!}</div>
                <a href="{{ route('pei-profiles.show', $proyecto->peiProfile->ancestors()->whereIsRoot()->first()->id ?? $proyecto->pei_profile_id) }}" class="btn btn-sm btn-outline-success mt-2">
                    <i class="fa fa-external-link-alt mr-1"></i> Ver en el PEI
                </a>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle mr-1"></i>
            Este proyecto no está vinculado al PEI. <strong>No podrá avanzar del estado Solicitud</strong> hasta que se vincule.
            <a href="{{ route('proyectos-institucionales.edit', $proyecto->id) }}" class="btn btn-sm btn-warning ml-2">Vincular ahora</a>
        </div>
        @endif

        {{-- Checklist ── --}}
        <div class="card shadow mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold"><i class="fa fa-tasks mr-1"></i> Checklist del Formulario</h6>
                <div class="d-flex align-items-center">
                    <div class="progress mr-2" style="width:80px;height:8px">
                        <div class="progress-bar bg-{{ $proyecto->pctChecklist()>=100?'success':($proyecto->pctChecklist()>=50?'warning':'danger') }}"
                             style="width:{{ $proyecto->pctChecklist() }}%"></div>
                    </div>
                    <small class="font-weight-bold">{{ $proyecto->pctChecklist() }}%</small>
                </div>
            </div>
            <div class="card-body p-0">
                <form id="formChecklist">
                    @csrf
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr><th>Ítem</th><th class="text-center" style="width:80px">Estado</th><th>Observación</th></tr>
                        </thead>
                        <tbody>
                            @foreach($checklistItems as $key => $label)
                            @php $check = $proyecto->checklist->firstWhere('item', $key); @endphp
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input checklist-item"
                                               id="check_{{ $key }}"
                                               name="checklist[{{ $key }}][completado]"
                                               value="1"
                                               {{ $check?->completado ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="check_{{ $key }}">{{ $label }}</label>
                                    </div>
                                    @if($check?->completado && $check->completadoPor)
                                    <small class="text-muted ml-4">
                                        <i class="fa fa-user mr-1"></i>{{ $check->completadoPor->name }}
                                        · {{ $check->completado_at?->format('d/m/Y') }}
                                    </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($check?->completado)
                                    <span class="badge badge-success"><i class="fa fa-check"></i></span>
                                    @else
                                    <span class="badge badge-light border">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="text" name="checklist[{{ $key }}][observacion]"
                                           class="form-control form-control-sm"
                                           value="{{ $check?->observacion }}"
                                           placeholder="Observación...">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-3 text-right">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-save mr-1"></i> Guardar Checklist
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- ── Columna derecha: presupuesto + historial ── --}}
    <div class="col-md-4">

        {{-- Presupuesto ── --}}
        <div class="card shadow mb-3">
            <div class="card-header"><h6 class="mb-0 font-weight-bold"><i class="fa fa-coins mr-1"></i> Presupuesto</h6></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Estimado</td>
                        <td class="text-right">Gs. {{ number_format($proyecto->presupuesto_estimado ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Aprobado</td>
                        <td class="text-right font-weight-bold">Gs. {{ number_format($proyecto->presupuesto_aprobado ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Ejecutado</td>
                        <td class="text-right text-{{ $proyecto->presupuesto_aprobado > 0 && $proyecto->presupuesto_ejecutado > $proyecto->presupuesto_aprobado ? 'danger' : 'success' }}">
                            Gs. {{ number_format($proyecto->presupuesto_ejecutado ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
                @if($proyecto->presupuesto_aprobado > 0)
                @php $pctPres = round($proyecto->presupuesto_ejecutado / $proyecto->presupuesto_aprobado * 100); @endphp
                <div class="progress mt-2" style="height:6px">
                    <div class="progress-bar bg-{{ $pctPres > 100 ? 'danger' : 'success' }}" style="width:{{ min($pctPres,100) }}%"></div>
                </div>
                <small class="text-muted">{{ $pctPres }}% ejecutado</small>
                @endif
            </div>
        </div>

        {{-- Historial ── --}}
        <div class="card shadow">
            <div class="card-header"><h6 class="mb-0 font-weight-bold"><i class="fa fa-history mr-1"></i> Historial de Estados</h6></div>
            <div class="card-body p-0" style="max-height:400px;overflow-y:auto">
                <ul class="list-group list-group-flush">
                    @forelse($proyecto->historial->sortByDesc('fecha') as $h)
                    <li class="list-group-item py-2">
                        <div class="d-flex justify-content-between">
                            <span class="badge {{ \App\Models\Proyectos\ProyectoInstitucional::estadoBadge($h->estado_nuevo) }}" style="font-size:.7rem">
                                {{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($h->estado_nuevo) }}
                            </span>
                            <small class="text-muted">{{ $h->fecha->format('d/m/Y H:i') }}</small>
                        </div>
                        @if($h->usuario)
                        <small class="text-muted"><i class="fa fa-user mr-1"></i>{{ $h->usuario->name }}</small>
                        @endif
                        @if($h->comentario)
                        <div style="font-size:.8rem" class="mt-1">{{ $h->comentario }}</div>
                        @endif
                    </li>
                    @empty
                    <li class="list-group-item text-muted text-center py-3"><em>Sin historial</em></li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>

{{-- ── Modal Cambiar Estado ── --}}
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-primary">
                <h4 class="modal-title">Avanzar Estado del Proyecto</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold">Nuevo estado <span class="text-danger">*</span></label>
                    <select id="nuevoEstado" class="form-control">
                        @foreach($transiciones as $est)
                        <option value="{{ $est }}">{{ \App\Models\Proyectos\ProyectoInstitucional::estadoLabel($est) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Comentario</label>
                    <textarea id="comentarioEstado" class="form-control" rows="3"
                              placeholder="Observaciones del cambio de estado..."></textarea>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarEstado">
                        <i class="fa fa-arrow-right mr-1"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
$(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    // ── Cambiar estado ────────────────────────────────────────────────────────
    $('#btnConfirmarEstado').on('click', function() {
        var estado     = $('#nuevoEstado').val();
        var comentario = $('#comentarioEstado').val();
        $(this).html('<i class="fa fa-spinner fa-spin mr-1"></i>').prop('disabled', true);

        $.post('{{ route('proyectos-institucionales.estado', $proyecto->id) }}', {
            estado: estado, comentario: comentario
        }, function(res) {
            toastr.success(res.success);
            $('#modalCambiarEstado').modal('hide');
            setTimeout(function() { location.reload(); }, 800);
        }).fail(function(xhr) {
            toastr.error(xhr.responseJSON?.error || 'Error al cambiar estado.');
            $('#btnConfirmarEstado').html('<i class="fa fa-arrow-right mr-1"></i> Confirmar').prop('disabled', false);
        });
    });

    // ── Guardar checklist ─────────────────────────────────────────────────────
    $('#formChecklist').on('submit', function(e) {
        e.preventDefault();
        $.post('{{ route('proyectos-institucionales.checklist', $proyecto->id) }}',
            $(this).serialize(),
            function(res) {
                toastr.success(res.success + ' (' + res.pct + '% completado)');
                if (res.completo) {
                    toastr.info('✅ Checklist completo. Ya podés enviar a homologación.');
                }
                setTimeout(function() { location.reload(); }, 1000);
            }
        ).fail(function() { toastr.error('Error al guardar checklist.'); });
    });
});
</script>
@stop
