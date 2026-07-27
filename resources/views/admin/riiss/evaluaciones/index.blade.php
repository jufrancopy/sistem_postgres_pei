@extends('layouts.master')
@section('title', 'Evaluaciones RIISS')

@push('styles')
<style>
.estado-badge { font-size:.7rem; padding:3px 8px; border-radius:20px; font-weight:600; }
.estado-borrador       { background:#e2e8f0; color:#475569; }
.estado-en_progreso    { background:#fef3c7; color:#92400e; }
.estado-completada     { background:#d1fae5; color:#065f46; }
.estado-verificada     { background:#dbeafe; color:#1e40af; }
.estado-rechazada      { background:#fee2e2; color:#991b1b; }
.pct-bar  { height:6px; border-radius:3px; background:#e5e7eb; overflow:hidden; }
.pct-fill { height:100%; border-radius:3px; transition:width .4s; }
.hover-bg:hover { background:#fef2f2; }
/* Asegurar botones circulares en esta vista (override local) */
.btn-circle {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 34px !important;
    height: 34px !important;
    padding: 0 !important;
    border-radius: 50% !important;
}
.btn-circle.btn-sm { width: 30px !important; height: 30px !important; }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-clipboard-check mr-2"></i>Evaluaciones de Establecimientos</h4>
        <p class="card-category">Historial de evaluaciones de cartera de servicios</p>
    </div>

    <nav class="bg-light rounded px-3 py-2 mb-0">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active">Evaluaciones</li>
        </ol>
    </nav>

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 font-weight-bold">
                <i class="fa fa-list mr-2 text-danger"></i>Todas las evaluaciones
            </h5>
            @role('Administrador')
            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalBuscarEst">
                <i class="fa fa-plus mr-1"></i>Nueva Evaluación
            </button>
            @endrole
        </div>

        @if($evaluaciones->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa fa-clipboard fa-3x mb-3 d-block" style="opacity:.3"></i>
            <p>No hay evaluaciones registradas aún.</p>
            @role('Administrador')
            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalBuscarEst">
                <i class="fa fa-plus mr-1"></i>Crear primera evaluación
            </button>
            @endrole
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Establecimiento</th>
                        <th>Fecha</th>
                        <th>Evaluador</th>
                        <th>Estado</th>
                        <th>Cumplimiento</th>
                        <th>Clasificación</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluaciones as $ev)
                    @php
                        $clasif = $ev->clasificacion_resultado;
                        $clasifColor = match($clasif) {
                            'CUMPLE'              => 'success',
                            'CUMPLE_PARCIALMENTE' => 'warning',
                            'NO_CUMPLE'           => 'danger',
                            default               => 'secondary',
                        };
                        $pct = $ev->porcentaje_cumplimiento ?? 0;
                        $pctColor = $pct >= 90 ? '#22c55e' : ($pct >= 70 ? '#f97316' : '#ef4444');
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $ev->id }}</td>
                        <td>
                            <strong>{{ $ev->establecimiento->nombre_oficial ?? '—' }}</strong><br>
                            <small class="text-muted">{{ $ev->id_establecimiento }}</small>
                        </td>
                        <td><small>{{ $ev->fecha_evaluacion?->format('d/m/Y') }}</small></td>
                        <td><small>{{ $ev->evaluador_nombre ?? '—' }}</small></td>
                        <td>
                            <span class="estado-badge estado-{{ $ev->estado }}">
                                {{ ucfirst(str_replace('_', ' ', $ev->estado)) }}
                            </span>
                        </td>
                        <td style="min-width:100px">
                            @if($pct > 0)
                            <div class="pct-bar">
                                <div class="pct-fill" style="width:{{ $pct }}%;background:{{ $pctColor }}"></div>
                            </div>
                            <small class="font-weight-bold" style="color:{{ $pctColor }}">{{ $pct }}%</small>
                            @else
                            <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>
                            @if($clasif)
                            <span class="badge badge-{{ $clasifColor }}">{{ str_replace('_', ' ', $clasif) }}</span>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('riiss.evaluaciones.nueva', $ev->id_establecimiento) }}?evaluacion={{ $ev->id }}" class="btn btn-primary btn-circle btn-sm mr-1" title="Revisar evaluación">
                                <i class="fa fa-play"></i>
                            </a>
                            <a href="{{ route('riiss.evaluaciones.show', $ev) }}" class="btn btn-info btn-circle btn-sm mr-1">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if(in_array($ev->estado, ['borrador','en_progreso']))
                            <a href="{{ route('riiss.evaluaciones.nueva', $ev->id_establecimiento) }}?evaluacion={{ $ev->id }}" class="btn btn-warning btn-circle btn-sm mr-1" title="Editar evaluación">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endif
                            <button class="btn btn-danger btn-circle btn-sm" title="Eliminar evaluación" onclick="eliminarEvaluacion({{ $ev->id }}, this)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $evaluaciones->links() }}</div>
        @endif

    </div>
</div>

{{-- Modal buscar establecimiento --}}
<div class="modal fade" id="modalBuscarEst" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#c62828,#e91e63)">
                <h5 class="modal-title text-white">
                    <i class="fa fa-plus mr-2"></i>Nueva Evaluación — Seleccioná el establecimiento
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <select id="modalBuscarSelect" class="form-control form-control-lg" style="width:100%"></select>
                </div>
                <div class="d-flex flex-wrap mb-3" style="gap:6px">
                    <button class="btn btn-sm btn-danger filtro-tipo" onclick="filtrarTipo(this,'')">Todos</button>
                    <button class="btn btn-sm btn-outline-secondary filtro-tipo" onclick="filtrarTipo(this,'HR')">Hospital Regional</button>
                    <button class="btn btn-sm btn-outline-secondary filtro-tipo" onclick="filtrarTipo(this,'US')">Unidad Sanitaria</button>
                    <button class="btn btn-sm btn-outline-secondary filtro-tipo" onclick="filtrarTipo(this,'CP')">Clínica Periférica</button>
                    <button class="btn btn-sm btn-outline-secondary filtro-tipo" onclick="filtrarTipo(this,'PS')">Puesto Sanitario</button>
                </div>
                <div id="modalResultados" style="max-height:380px;overflow-y:auto;border:1px solid #e5e7eb;border-radius:8px;display:none">
                    <!-- select2 will show results -->
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted mr-auto">Hacé clic en "Evaluar" para iniciar</small>
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var tipoFiltro = '';
var buscarTimer;
var BUSCAR_URL = '{{ route("riiss.establecimientos.buscar") }}';
var COLORES_COMPLEJIDAD = {
    'No Hospitalario de Baja Complejidad':    '#22c55e',
    'No Hospitalario de Mediana Complejidad': '#84cc16',
    'Hospitalario 1 Baja Complejidad':        '#eab308',
    'Hospitalario 2 Mediana Complejidad':     '#f97316',
    'Hospitalario 3 Alta Complejidad':        '#ef4444'
};

$(document).ready(function() {
    $('#modalBuscarEst').on('shown.bs.modal', function() {
        initModalBuscarSelect();
    });

    function initModalBuscarSelect() {
        if ($('#modalBuscarSelect').hasClass('select2-hidden-accessible')) return;
        $('#modalBuscarSelect').select2({
            placeholder: 'Escribí el nombre del establecimiento...',
            width: '100%',
            dropdownParent: $('#modalBuscarEst .modal-content'),
            minimumInputLength: 1,
            ajax: {
                url: BUSCAR_URL,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        buscar: params.term || '',
                        tipo: tipoFiltro || '',
                        per_page: 20,
                        page: params.page || 1,
                        json: true
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    const results = (data.data && data.data.data ? data.data.data : data.data) || [];
                    return {
                        results: results.map(function(e) {
                            return {
                                id: e.id,
                                text: e.nombre,
                                departamento: e.departamento,
                                complejidad: e.complejidad_label || e.complejidad
                            };
                        }),
                        pagination: {
                            more: (data.data && data.data.current_page ? data.data.current_page < data.data.last_page : false)
                        }
                    };
                }
            },
            templateResult: function(item) {
                if (!item.id) return item.text;
                return $('<div><strong>' + item.text + '</strong><br><small class="text-muted">' + (item.departamento || '') + ' · ' + (item.complejidad || '') + '</small></div>');
            }
        });

        $('#modalBuscarSelect').on('select2:select', function(e) {
            const id = e.params.data.id;
            if (id) window.location = '/riiss/evaluaciones/nueva/' + id;
        });
    }
});

function filtrarTipo(btn, tipo) {
    tipoFiltro = tipo;
    $('.filtro-tipo').removeClass('btn-danger').addClass('btn-outline-secondary');
    $(btn).removeClass('btn-outline-secondary').addClass('btn-danger');
    if ($('#modalBuscarSelect').hasClass('select2-hidden-accessible')) {
        $('#modalBuscarSelect').val(null).trigger('change');
    }
}

function buscarEstablecimientos(q) {
    $('#modalResultados').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-danger"></div></div>');

    $.ajax({
        url: BUSCAR_URL,
        method: 'GET',
        data: { buscar: q, tipo: tipoFiltro, per_page: 20, page: 1 },
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(r) {
            if (!r.ok || !r.data || !r.data.data || !r.data.data.length) {
                $('#modalResultados').html('<div class="text-center py-4 text-muted"><i class="fa fa-search fa-2x mb-2 d-block" style="opacity:.3"></i>Sin resultados</div>');
                return;
            }

            var html = '';
            $.each(r.data.data, function(i, e) {
                var color = COLORES_COMPLEJIDAD[e.complejidad] || '#6b7280';
                var evalBadge = e.tiene_ultima_evaluacion
                    ? '<span class="badge badge-success ml-2" style="font-size:.65rem">Ya evaluado</span>'
                    : '';
                var label = e.complejidad_label || e.complejidad;

                html += '<div class="d-flex align-items-center p-3 border-bottom hover-bg" style="cursor:pointer" onclick="window.location=\'/riiss/evaluaciones/nueva/' + e.id + '\'">'
                    + '<div class="flex-grow-1">'
                    + '<div class="font-weight-bold">' + e.nombre + evalBadge + '</div>'
                    + '<div class="mt-1" style="display:flex;flex-wrap:wrap;gap:8px">'
                    + '<small class="text-muted">' + e.id + '</small>'
                    + '<span style="background:' + color + ';color:#fff;font-size:.65rem;padding:2px 8px;border-radius:20px;font-weight:600">' + label + '</span>'
                    + '<small class="text-muted"><i class="fa fa-map-marker-alt mr-1"></i>' + e.departamento + '</small>'
                    + '</div></div>'
                    + '<span class="btn btn-danger btn-circle btn-sm ml-3" style="flex-shrink:0"><i class="fa fa-clipboard-check mr-1"></i>Evaluar</span>'
                    + '</div>';
            });

            $('#modalResultados').html(html);
        },
        error: function(xhr) {
            var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error ' + xhr.status;
            $('#modalResultados').html('<div class="text-center py-4 text-danger small">' + msg + '</div>');
        }
    });
}

function mostrarToast(msg, tipo) {
    const color = tipo === 'success' ? '#22c55e' : '#ef4444';
    const toast = $(`<div style="position:fixed;bottom:24px;right:24px;background:${color};color:#fff;padding:12px 20px;border-radius:8px;z-index:9999;font-size:.9rem;box-shadow:0 4px 12px rgba(0,0,0,.2)">${msg}</div>`);
    $('body').append(toast);
    setTimeout(() => toast.fadeOut(400, () => toast.remove()), 3000);
}

function eliminarEvaluacion(id, btn) {
    const doDelete = () => {
        $(btn).prop('disabled', true);
        $.ajax({
            url: `/riiss/evaluaciones/${id}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(r) {
                if (r.ok) {
                    mostrarToast('Evaluación eliminada', 'success');
                    $(btn).closest('tr').fadeOut(300, function() { $(this).remove(); });
                } else {
                    alert('No se pudo eliminar la evaluación');
                    $(btn).prop('disabled', false);
                }
            },
            error: function() {
                alert('Error al eliminar');
                $(btn).prop('disabled', false);
            }
        });
    };

    if (typeof Swal !== 'undefined' && Swal.fire) {
        Swal.fire({
            title: 'Eliminar evaluación',
            text: `¿Eliminar evaluación #${id}? Esta acción marcará la evaluación como eliminada.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33'
        }).then((result) => { if (result.isConfirmed) doDelete(); });
    } else if (typeof swal !== 'undefined') {
        swal({
            title: 'Eliminar evaluación',
            text: `¿Eliminar evaluación #${id}?`,
            icon: 'warning',
            buttons: ['Cancelar','Eliminar'],
            dangerMode: true,
        }).then(function(willDelete){ if (willDelete) doDelete(); });
    } else {
        if (confirm('¿Eliminar evaluación #' + id + '?')) doDelete();
    }
}
</script>
@endsection
