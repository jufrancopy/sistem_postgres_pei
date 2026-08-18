@extends('layouts.master')
@section('title', 'Proyectos Institucionales — SCPI')

@section('content')

@php $peiProfileId = request()->route('profileId') ?? request('pei_profile_id'); @endphp

<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title"><i class="fa fa-project-diagram mr-2"></i>Seguimiento y Control de Proyectos Institucionales</h4>
        <p class="card-category">Ciclo de vida completo — vinculado al Plan Estratégico</p>
    </div>

    <nav aria-label="breadcrumb" class="bg-light rounded p-3 mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('proyectos-dashboard') }}">Proyectos</a></li>
            @if($peiProfileId)
            <li class="breadcrumb-item"><a href="{{ route('pei-profiles.proceso', $peiProfileId) }}">Perfil PEI</a></li>
            @endif
            <li class="breadcrumb-item active">Proyectos Institucionales</li>
        </ol>
    </nav>

    <div class="card-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            @php $kpiCards = [
                ['label'=>'Total Proyectos',   'valor'=>$kpis['total'],        'color'=>'info',    'icon'=>'fa-folder'],
                ['label'=>'Activos',           'valor'=>$kpis['activos'],      'color'=>'primary', 'icon'=>'fa-play-circle'],
                ['label'=>'En Ejecución',      'valor'=>$kpis['en_ejecucion'], 'color'=>'success', 'icon'=>'fa-rocket'],
                ['label'=>'Sin vincular PEI',  'valor'=>$kpis['sin_pei'],      'color'=>'warning', 'icon'=>'fa-unlink'],
                ['label'=>'Finalizados',       'valor'=>$kpis['finalizados'],  'color'=>'secondary','icon'=>'fa-check-double'],
            ]; @endphp
            @foreach($kpiCards as $k)
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card border-left-{{ $k['color'] }} shadow h-100 py-2">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-{{ $k['color'] }} text-uppercase mb-1">{{ $k['label'] }}</div>
                        <div class="h4 mb-0 font-weight-bold">{{ $k['valor'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3 d-flex flex-column justify-content-center">
                <a href="{{ route('proyectos-institucionales.create-for-perfil', $peiProfileId) }}" class="btn btn-success btn-block mb-1">
                    <i class="fa fa-plus mr-1"></i> Nuevo Proyecto
                </a>
                @if($peiProfileId)
                <button type="button" class="btn btn-sm btn-outline-info btn-block" data-toggle="modal" data-target="#modalQrSolicitud">
                    <i class="fa fa-qrcode mr-1"></i> QR Solicitud PEI
                </button>
                @endif
            </div>
        </div>

        {{-- ── Filtros ── --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <select id="filtroEstado" class="form-control form-control-sm">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" id="filtroBuscar" class="form-control form-control-sm" placeholder="Buscar por nombre...">
            </div>
        </div>

        {{-- ── DataTable ── --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="tablaProyectos">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Dependencia Solicitante</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">PEI</th>
                        <th class="text-center">Checklist</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    var table = $('#tablaProyectos').DataTable({
        processing: true,
        serverSide: true,
        language: {
            emptyTable: 'No hay proyectos registrados',
            processing: 'Procesando...',
            search: 'Buscar:',
            zeroRecords: 'Sin resultados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_',
            paginate: { next: 'Siguiente', previous: 'Anterior' }
        },
        ajax: {
            url: '{{ route('proyectos-institucionales.index', $peiProfileId) }}',
            data: function(d) {
                d.estado          = $('#filtroEstado').val();
                d.q               = $('#filtroBuscar').val();
                d.pei_profile_id  = '{{ $peiProfileId }}';
            }
        },
        columns: [
            { data: 'DT_RowIndex',    name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'codigo',         name: 'codigo' },
            { data: 'nombre',         name: 'nombre' },
            { data: 'dependencia_solicitante.dependency', name: 'dependenciaSolicitante.dependency', defaultContent: '—' },
            { data: 'estado_badge',   name: 'estado', className: 'text-center' },
            { data: 'pei_vinculo',    name: 'pei_profile_id', className: 'text-center', orderable: false },
            { data: 'checklist_pct',  name: 'checklist_pct', className: 'text-center', orderable: false },
            { data: 'action',         name: 'action', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    $('#filtroEstado, #filtroBuscar').on('change keyup', function() { table.draw(); });
});
</script>

@if($peiProfileId)
{{-- Modal QR Solicitud de Proyecto --}}
<div class="modal fade" id="modalQrSolicitud" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h6 class="modal-title text-white"><i class="fa fa-qrcode mr-1"></i> Solicitud de Proyecto PEI</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-3" style="font-size:.85rem">Escaneá el QR para acceder al formulario público de solicitud de proyecto para este plan.</p>
                {!! QrCode::size(200)->generate(route('proyectos.solicitar.form', $peiProfileId)) !!}
                <div class="mt-3">
                    <a href="{{ route('proyectos.solicitar.form', $peiProfileId) }}" target="_blank"
                       class="btn btn-sm btn-outline-primary btn-block">
                        <i class="fa fa-external-link-alt mr-1"></i> Abrir Formulario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@stop
