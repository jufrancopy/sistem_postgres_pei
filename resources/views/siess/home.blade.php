@extends('layouts.master')
@section('title', 'Inicio — ' . Auth::user()->name)

@section('content')

{{-- ── Header ── --}}
<div class="card mb-3">
    <div class="card-header card-header-info">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">
                    <i class="fa fa-user-circle mr-2"></i>
                    {{ Auth::user()->name }}
                </h4>
                @if($organigrama)
                <p class="card-category mb-0 mt-1">
                    <i class="fa fa-building mr-1"></i>
                    {{ $organigrama->dependency }}
                    @if($organigrama->parent)
                        <span class="opacity-75"> · {{ $organigrama->parent->dependency }}</span>
                    @endif
                </p>
                @endif
            </div>
            <div class="text-right">
                @if($pendientesParaValidar->count() > 0)
                <span class="badge badge-warning" style="font-size:.9rem;padding:8px 12px">
                    <i class="fa fa-clock mr-1"></i>
                    {{ $pendientesParaValidar->count() }} pendiente(s) de validación
                </span>
                @else
                <span class="badge badge-success" style="font-size:.9rem;padding:8px 12px">
                    <i class="fa fa-check-circle mr-1"></i> Al día
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── Extractos pendientes de validación ── --}}
@if($pendientesParaValidar->isNotEmpty())
<div class="mb-4">
    <div class="d-flex align-items-center mb-3">
        <h5 class="font-weight-bold mb-0">
            <i class="fa fa-exclamation-circle text-warning mr-2"></i>
            Extractos que requieren tu validación
        </h5>
        <span class="badge badge-warning ml-2">{{ $pendientesParaValidar->count() }}</span>
    </div>

    @foreach($pendientesParaValidar as $extracto)
    @php
        $dias = $extracto->diasRestantes();
        $colorDias = $dias === 0 ? 'danger' : ($dias <= 2 ? 'warning' : 'info');
    @endphp
    <div class="card shadow mb-3 border-left-{{ $colorDias }}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <span class="badge badge-secondary mr-1">{{ $extracto->modulo->codigo }}</span>
                <strong>{{ $extracto->indicador->nombre }}</strong>
                <span class="text-muted ml-2">— {{ $extracto->periodo->nombre }}</span>
            </div>
            <div class="d-flex align-items-center">
                @if($dias === 0)
                    <span class="badge badge-danger mr-3"><i class="fa fa-fire mr-1"></i> Vence HOY</span>
                @elseif($dias <= 2)
                    <span class="badge badge-warning mr-3"><i class="fa fa-clock mr-1"></i> {{ $dias }} día(s) restante(s)</span>
                @else
                    <span class="badge badge-info mr-3"><i class="fa fa-calendar mr-1"></i> {{ $dias }} días restantes</span>
                @endif
                <small class="text-muted">Límite: {{ $extracto->fecha_limite_validacion?->format('d/m/Y') }}</small>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- Columna izquierda: info del extracto --}}
                <div class="col-md-8">
                    {{-- Resumen --}}
                    @if($extracto->resumen)
                    <div class="mb-3">
                        <label class="text-muted text-uppercase" style="font-size:.75rem;letter-spacing:.05em">
                            <i class="fa fa-align-left mr-1"></i> Descripción del extracto
                        </label>
                        <p class="mb-0">{{ $extracto->resumen }}</p>
                    </div>
                    @endif

                    {{-- Datos JSON si existen --}}
                    @if($extracto->datos)
                    <div class="mb-3">
                        <label class="text-muted text-uppercase" style="font-size:.75rem;letter-spacing:.05em">
                            <i class="fa fa-table mr-1"></i> Datos del extracto
                        </label>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <tbody>
                                    @foreach($extracto->datos as $key => $value)
                                    <tr>
                                        <td class="font-weight-bold bg-light" style="width:40%">{{ ucfirst(str_replace('_',' ',$key)) }}</td>
                                        <td>
                                            @if(is_array($value))
                                                <code>{{ json_encode($value) }}</code>
                                            @else
                                                {{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Historial de validaciones --}}
                    @if($extracto->validaciones->isNotEmpty())
                    <div class="mb-2">
                        <label class="text-muted text-uppercase" style="font-size:.75rem;letter-spacing:.05em">
                            <i class="fa fa-history mr-1"></i> Historial
                        </label>
                        <ul class="list-unstyled mb-0">
                            @foreach($extracto->validaciones as $v)
                            <li class="mb-1" style="font-size:.85rem">
                                <span class="badge badge-secondary">{{ $v->accionLabel() }}</span>
                                <span class="text-muted ml-1">{{ $v->fecha->format('d/m/Y H:i') }}</span>
                                @if($v->comentario)
                                    <span class="ml-1">— {{ $v->comentario }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                {{-- Columna derecha: info del módulo + acciones --}}
                <div class="col-md-4">
                    <div class="card bg-light mb-3">
                        <div class="card-body py-2">
                            <div class="mb-2">
                                <small class="text-muted text-uppercase">Módulo</small>
                                <div class="font-weight-bold">{{ $extracto->modulo->nombre }}</div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted text-uppercase">Indicador</small>
                                <div>{{ $extracto->indicador->codigo }} — {{ $extracto->indicador->nombre }}</div>
                                @if($extracto->indicador->unidad)
                                <small class="text-muted">Unidad: {{ $extracto->indicador->unidad }}</small>
                                @endif
                            </div>
                            <div class="mb-2">
                                <small class="text-muted text-uppercase">Período</small>
                                <div>{{ $extracto->periodo->nombre }}</div>
                            </div>
                            <div>
                                <small class="text-muted text-uppercase">Cargado por</small>
                                <div>{{ $extracto->cargadoPor?->name ?? '—' }}</div>
                                <small class="text-muted">{{ $extracto->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-block mb-2 aprobarExtractoHome"
                                data-id="{{ $extracto->id }}">
                            <i class="fa fa-check mr-2"></i>
                            Aprobar este extracto
                        </button>
                        <button class="btn btn-danger btn-block objetarExtractoHome"
                                data-id="{{ $extracto->id }}">
                            <i class="fa fa-times mr-2"></i>
                            Objetar este extracto
                        </button>
                    </div>

                    <div class="mt-2 text-center">
                        <small class="text-muted">
                            <i class="fa fa-info-circle mr-1"></i>
                            Art. 8 Res. 266/2022 — Si no respondés antes del
                            {{ $extracto->fecha_limite_validacion?->format('d/m/Y') }},
                            se aprobará automáticamente.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-success mb-4">
    <i class="fa fa-check-circle mr-2"></i>
    No tenés extractos pendientes de validación. ¡Estás al día!
</div>
@endif

{{-- ── Historial reciente (aprobados/objetados) ── --}}
@if($historialReciente->isNotEmpty())
<div class="card shadow">
    <div class="card-header">
        <h6 class="mb-0 font-weight-bold">
            <i class="fa fa-history mr-2"></i> Historial reciente de tu dirección
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Módulo</th>
                    <th>Indicador</th>
                    <th>Período</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Fecha respuesta</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historialReciente as $e)
                <tr>
                    <td><span class="badge badge-secondary">{{ $e->modulo->codigo }}</span></td>
                    <td>{{ $e->indicador->nombre }}</td>
                    <td>{{ $e->periodo->nombre }}</td>
                    <td class="text-center">
                        <span class="badge {{ \App\Models\Estadistica\SiessExtracto::estadoBadge($e->estado) }}">
                            {{ \App\Models\Estadistica\SiessExtracto::estadoLabel($e->estado) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <small>{{ $e->fecha_respuesta?->format('d/m/Y') ?? '—' }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Modal Objetar ── --}}
<div class="modal fade" id="modalObjetarHome" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card-header card-header-danger">
                <h4 class="modal-title">Objetar Extracto</h4>
                <small class="text-white opacity-75">Explicá el motivo para que Planificación pueda corregir y reenviar</small>
            </div>
            <div class="modal-body">
                <input type="hidden" id="objetarHomeId">
                <div class="form-group">
                    <label class="font-weight-bold">Motivo de la objeción <span class="text-danger">*</span></label>
                    <textarea id="observacionHome" class="form-control" rows="5" required
                        placeholder="Describa detalladamente el motivo de la objeción (mínimo 10 caracteres)..."></textarea>
                    <small class="text-muted">Este comentario quedará registrado en el historial del extracto.</small>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarObjecion">
                        <i class="fa fa-times mr-1"></i> Confirmar Objeción
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

    // Aprobar
    $('body').on('click', '.aprobarExtractoHome', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Aprobar este extracto?',
            html: 'El dato quedará disponible para <strong>reportes gerenciales</strong> y el Observatorio Institucional.',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-check mr-1"></i> Sí, aprobar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post('{{ url('siess/extractos') }}/' + id + '/aprobar', {}, function(res) {
                    toastr.success(res.success);
                    setTimeout(function() { location.reload(); }, 1500);
                }).fail(function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'Error al aprobar');
                });
            }
        });
    });

    // Objetar — abrir modal
    $('body').on('click', '.objetarExtractoHome', function() {
        $('#objetarHomeId').val($(this).data('id'));
        $('#observacionHome').val('');
        $('#modalObjetarHome').modal('show');
    });

    // Objetar — confirmar
    $('#btnConfirmarObjecion').on('click', function() {
        var id = $('#objetarHomeId').val();
        var obs = $('#observacionHome').val().trim();
        if (obs.length < 10) {
            toastr.warning('El motivo debe tener al menos 10 caracteres.');
            return;
        }
        $(this).html('<i class="fa fa-spinner fa-spin mr-1"></i> Enviando...').prop('disabled', true);
        $.post('{{ url('siess/extractos') }}/' + id + '/objetar', { observacion: obs }, function(res) {
            toastr.warning(res.success);
            $('#modalObjetarHome').modal('hide');
            setTimeout(function() { location.reload(); }, 1500);
        }).fail(function(xhr) {
            toastr.error(xhr.responseJSON?.error || 'Error al objetar');
            $('#btnConfirmarObjecion').html('<i class="fa fa-times mr-1"></i> Confirmar Objeción').prop('disabled', false);
        });
    });
});
</script>
@stop
