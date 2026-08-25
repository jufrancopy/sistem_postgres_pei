@extends('layouts.master')

@section('title', 'Gestión de Solicitudes de Ajuste de Estructura Organizacional')

@section('content')
<div class="container-fluid">

    {{-- Hero Banner --}}
    <div class="card mb-4 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 14px;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-1 mb-2" style="font-size:0.75rem; border-radius:20px;">
                        <i class="fa fa-sitemap mr-1"></i> ESTRUCTURA ORGANIZACIONAL & PEI
                    </span>
                    <h2 class="font-weight-bold mb-1 text-white">Solicitudes de Reorganización Estructural</h2>
                    <p class="mb-0 text-white-50" style="font-size:0.95rem;">
                        Recepción, evaluación técnica y dictamen de pedidos de ajuste de dependencias alineados al Plan Estratégico.
                    </p>
                </div>
                <div class="mt-3 mt-md-0 d-flex gap-2">
                    <button type="button" class="btn btn-warning text-dark font-weight-bold btn-round shadow-sm mr-2" data-toggle="modal" data-target="#modalCompartirQr">
                        <i class="fa fa-qrcode mr-1"></i> Código QR / Formulario
                    </button>
                    <a href="{{ route('proyectos.solicitar.ajuste-estructura.form', $targetPeiId) }}" target="_blank" class="btn btn-light text-primary font-weight-bold btn-round shadow-sm">
                        <i class="fa fa-plus-circle mr-1"></i> Nueva Solicitud
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Fila de KPIs --}}
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card p-3 h-100 shadow-sm border-0" style="border-radius:10px; border-left: 4px solid #0284c7 !important;">
                <div class="text-muted small font-weight-bold text-uppercase">Total Pedidos</div>
                <div class="h3 font-weight-bold text-dark mb-0">{{ $kpis['total'] }}</div>
                <small class="text-muted">Expedientes ingresados</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card p-3 h-100 shadow-sm border-0" style="border-radius:10px; border-left: 4px solid #3b82f6 !important;">
                <div class="text-muted small font-weight-bold text-uppercase">Recibidos</div>
                <div class="h3 font-weight-bold text-primary mb-0">{{ $kpis['solicitud'] }}</div>
                <small class="text-muted">Pendientes de revisión</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card p-3 h-100 shadow-sm border-0" style="border-radius:10px; border-left: 4px solid #f59e0b !important;">
                <div class="text-muted small font-weight-bold text-uppercase">En Análisis</div>
                <div class="h3 font-weight-bold text-warning mb-0">{{ $kpis['en_analisis'] }}</div>
                <small class="text-muted">En evaluación técnica</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card p-3 h-100 shadow-sm border-0" style="border-radius:10px; border-left: 4px solid #f97316 !important;">
                <div class="text-muted small font-weight-bold text-uppercase">Observados</div>
                <div class="h3 font-weight-bold text-orange mb-0">{{ $kpis['observado'] }}</div>
                <small class="text-muted">Requieren ajustes</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card p-3 h-100 shadow-sm border-0" style="border-radius:10px; border-left: 4px solid #10b981 !important;">
                <div class="text-muted small font-weight-bold text-uppercase">Aprobados</div>
                <div class="h3 font-weight-bold text-success mb-0">{{ $kpis['aprobado'] }}</div>
                <small class="text-muted">Dictamen favorable</small>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3 mb-xl-0">
            <div class="card p-3 h-100 shadow-sm border-0" style="border-radius:10px; border-left: 4px solid #ef4444 !important;">
                <div class="text-muted small font-weight-bold text-uppercase">Rechazados</div>
                <div class="h3 font-weight-bold text-danger mb-0">{{ $kpis['rechazado'] }}</div>
                <small class="text-muted">No procedentes</small>
            </div>
        </div>
    </div>

    {{-- Tabla de Solicitudes --}}
    <div class="card shadow-sm border-0" style="border-radius:12px; overflow:hidden;">
        <div class="card-header bg-white p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h5 class="font-weight-bold text-dark mb-2 mb-md-0">
                <i class="fa fa-list-alt text-primary mr-2"></i> Bandeja de Solicitudes Recibidas
            </h5>
            <div class="d-flex align-items-center">
                <select id="filtroEstado" class="form-control form-control-sm mr-2" style="width:180px;">
                    <option value="">-- Todos los Estados --</option>
                    @foreach($estados as $key => $lbl)
                        <option value="{{ $key }}">{{ $lbl }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnRecargarTabla">
                    <i class="fa fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped w-100" id="tablaSolicitudesEstructura">
                    <thead class="bg-light">
                        <tr>
                            <th style="width:120px;">Radicación</th>
                            <th>Solicitante & Dependencia</th>
                            <th>Alineación Estratégica (PEI)</th>
                            <th style="width:100px;">Cambios</th>
                            <th style="width:140px;">Estado</th>
                            <th style="width:110px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal para Compartir Código QR y Enlace --}}
<div class="modal fade" id="modalCompartirQr" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title font-weight-bold"><i class="fa fa-qrcode mr-2"></i> Código QR del Formulario Oficial</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="text-muted small mb-3">
                    Compartí este Código QR o enlace con las Gerencias, Direcciones y Unidades para que radiquen sus solicitudes de ajuste organizacional vinculadas al Plan Estratégico.
                </p>
                <div class="p-3 bg-light rounded border d-inline-block mb-3 shadow-sm">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate(route('proyectos.solicitar.ajuste-estructura.form', $targetPeiId)) !!}
                </div>
                <div class="input-group mt-2">
                    <input type="text" class="form-control text-center font-weight-bold" id="linkFormulario" value="{{ route('proyectos.solicitar.ajuste-estructura.form', $targetPeiId) }}" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary font-weight-bold" type="button" onclick="copiarEnlace()">
                            <i class="fa fa-copy mr-1"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light p-2">
                <a href="{{ route('proyectos.solicitar.ajuste-estructura.form', $targetPeiId) }}" target="_blank" class="btn btn-primary btn-round px-4">
                    <i class="fa fa-external-link-alt mr-1"></i> Abrir Formulario
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var table = $('#tablaSolicitudesEstructura').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.estructura-solicitudes.index') }}",
            data: function (d) {
                d.estado = $('#filtroEstado').val();
            }
        },
        columns: [
            { data: 'codigo_formatted', name: 'codigo' },
            { data: 'solicitante_info', name: 'solicitante_nombre' },
            { data: 'pei_vinculo', name: 'pei_profile_id' },
            { data: 'items_count', name: 'items_count', orderable: false, searchable: false },
            { data: 'estado_badge', name: 'estado' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        }
    });

    $('#filtroEstado').on('change', function () {
        table.ajax.reload();
    });

    $('#btnRecargarTabla').on('click', function () {
        table.ajax.reload();
    });
});

function copiarEnlace() {
    var input = document.getElementById('linkFormulario');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function () {
        toastr.success('Enlace copiado al portapapeles.');
    });
}
</script>
@endpush
