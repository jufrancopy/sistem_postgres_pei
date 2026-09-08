@extends('layouts.master')

@section('title', 'Calendario Ejecutivo de Eventos e Hitos')

@section('head')
    <link rel="stylesheet" href="{{ asset('material/css/plugins/fullcalendar.min.css') }}">
    <style>
        .fc-toolbar {
            margin-bottom: 1.5rem !important;
        }
        .fc-toolbar h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a202c;
            text-transform: capitalize;
        }
        .fc-button {
            border-radius: 6px !important;
            box-shadow: none !important;
            text-shadow: none !important;
            font-weight: 600 !important;
            padding: 0.4rem 0.8rem !important;
            background: #fff !important;
            color: #4a5568 !important;
            border: 1px solid #e2e8f0 !important;
        }
        .fc-button.fc-state-active {
            background: #4f46e5 !important;
            color: #fff !important;
            border-color: #4f46e5 !important;
        }
        .fc-event {
            border-radius: 6px !important;
            padding: 3px 6px !important;
            font-size: 0.8rem !important;
            border: none !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.15) !important;
        }
        .filter-chip {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-right: 6px;
            margin-bottom: 6px;
        }
        .legend-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
    </style>
@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row align-items-center mb-4">
            <div class="col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('eventos.index') }}">Eventos Institucionales</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Calendario Integral</li>
                    </ol>
                </nav>
                <h3 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-calendar-alt text-primary mr-2"></i> Calendario Ejecutivo de Eventos & Hitos
                </h3>
                <p class="text-muted mb-0">Visualización cronológica interactiva de eventos, fases de ejecución y vencimiento de tareas operativas.</p>
            </div>
            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary btn-round">
                    <i class="fas fa-list mr-1"></i> Vista Tabla
                </a>
                <a href="{{ route('eventos.create') }}" class="btn btn-primary btn-round shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo Evento
                </a>
            </div>
        </div>

        <!-- Filtros Rápidos y Leyenda -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1 d-block">Filtrar por Plan PEI</label>
                        <select id="filter_pei_id" class="form-control select2 select-picker" style="width: 100%;">
                            <option value="">Todos los Planes PEI</option>
                            @foreach($peis as $p)
                                <option value="{{ $p->id }}" {{ request('pei_profile_id') == $p->id ? 'selected' : '' }}>{{ $p->nombre }} ({{ $p->periodo_texto }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 mb-2 mb-md-0">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1 d-block">Filtrar por Responsable</label>
                        <select id="filter_user_id" class="form-control select2" style="width: 100%;">
                            <option value="">Todos los Responsables</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-4 text-md-right mt-3 mt-md-0">
                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-1 d-block">Convención de Colores</label>
                        <div>
                            <span class="filter-chip bg-light text-dark border">
                                <span class="legend-indicator" style="background-color: #6366f1;"></span> Eventos
                            </span>
                            <span class="filter-chip bg-light text-dark border">
                                <span class="legend-indicator" style="background-color: #3b82f6;"></span> Pasos / Fases
                            </span>
                            <span class="filter-chip bg-light text-dark border">
                                <span class="legend-indicator" style="background-color: #ef4444;"></span> Tareas Vencidas
                            </span>
                            <span class="filter-chip bg-light text-dark border">
                                <span class="legend-indicator" style="background-color: #10b981;"></span> Tareas Completadas
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario Principal -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div id="fullCalendarExecutive"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Rápido de Ítem del Calendario -->
<div class="modal fade" id="calendarItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" id="modalHeaderBg">
                <h5 class="modal-title font-weight-bold" id="modalItemTitle">Detalle del Ítem</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <span id="modalItemBadge" class="badge"></span>
                    <span id="modalItemStatus" class="badge"></span>
                </div>
                <h6 class="font-weight-bold text-muted text-uppercase text-xs mb-1">Descripción / Detalle:</h6>
                <p id="modalItemDescription" class="text-dark mb-3 text-sm"></p>

                <div class="row bg-light rounded p-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block"><i class="far fa-calendar-alt mr-1"></i> Fecha Inicio:</small>
                        <strong id="modalItemStart" class="text-xs text-dark">-</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block"><i class="far fa-calendar-check mr-1"></i> Fecha Límite / Fin:</small>
                        <strong id="modalItemEnd" class="text-xs text-dark">-</strong>
                    </div>
                </div>

                <div id="modalResponsibleSection" class="mb-3 d-none">
                    <small class="text-muted d-block"><i class="fas fa-user-tag mr-1"></i> Responsable Asignado:</small>
                    <span id="modalItemResponsible" class="font-weight-bold text-sm text-dark"></span>
                </div>

                <div id="modalParentSection" class="mb-2 d-none">
                    <small class="text-muted d-block"><i class="fas fa-sitemap mr-1"></i> Evento / Paso Asociado:</small>
                    <span id="modalItemParent" class="text-sm font-weight-bold text-primary"></span>
                </div>
            </div>
            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-secondary btn-sm btn-round" data-dismiss="modal">Cerrar</button>
                <a id="modalItemActionBtn" href="#" class="btn btn-primary btn-sm btn-round">
                    <i class="fas fa-external-link-alt mr-1"></i> Ir al Evento
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('material/js/plugins/moment.min.js') }}"></script>
<script src="{{ asset('material/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('material/js/plugins/sweetalert2.js') }}"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4' });

    var calendarEl = $('#fullCalendarExecutive');

    calendarEl.fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Agenda'
        },
        locale: 'es',
        defaultView: 'month',
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: "{{ route('eventos.feed') }}",
                dataType: 'json',
                data: {
                    pei_profile_id: $('#filter_pei_id').val(),
                    user_id: $('#filter_user_id').val(),
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                },
                success: function(doc) {
                    callback(doc);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los eventos del calendario.'
                    });
                }
            });
        },
        eventClick: function(calEvent, jsEvent, view) {
            var props = calEvent.extendedProps || {};
            var tipo = props.tipo || 'evento';
            
            $('#modalItemTitle').text(calEvent.title);
            $('#modalItemDescription').text(props.descripcion || 'Sin descripción adicional.');
            $('#modalItemStart').text(calEvent.start ? calEvent.start.format('DD/MM/YYYY') : '-');
            $('#modalItemEnd').text(calEvent.end ? calEvent.end.format('DD/MM/YYYY') : (calEvent.start ? calEvent.start.format('DD/MM/YYYY') : '-'));
            
            // Header background by type
            var headerBg = '#4f46e5';
            if (tipo === 'tarea') {
                headerBg = props.completada ? '#10b981' : (props.vencida ? '#ef4444' : '#f59e0b');
            } else if (tipo === 'paso') {
                headerBg = '#3b82f6';
            }
            $('#modalHeaderBg').css('background', headerBg);
            
            // Badges
            $('#modalItemBadge').text(tipo.toUpperCase()).attr('class', 'badge badge-dark');
            if (props.estado) {
                $('#modalItemStatus').text(props.estado.toUpperCase()).attr('class', 'badge badge-secondary ml-1');
            } else {
                $('#modalItemStatus').text('').attr('class', 'd-none');
            }

            // Responsable
            if (props.responsable) {
                $('#modalItemResponsible').text(props.responsable);
                $('#modalResponsibleSection').removeClass('d-none');
            } else {
                $('#modalResponsibleSection').addClass('d-none');
            }

            // Parent
            if (props.evento_nombre) {
                $('#modalItemParent').text(props.evento_nombre + (props.paso_nombre ? ' → ' + props.paso_nombre : ''));
                $('#modalParentSection').removeClass('d-none');
            } else {
                $('#modalParentSection').addClass('d-none');
            }

            // Action URL
            if (props.url_evento) {
                $('#modalItemActionBtn').attr('href', props.url_evento).removeClass('d-none');
            } else {
                $('#modalItemActionBtn').addClass('d-none');
            }

            $('#calendarItemModal').modal('show');
        }
    });

    $('#filter_pei_id, #filter_user_id').on('change', function() {
        calendarEl.fullCalendar('refetchEvents');
    });
});
</script>
@endsection
