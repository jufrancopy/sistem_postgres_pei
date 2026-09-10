@extends('layouts.master')

@section('title', 'Validación de Especialidades Médicas')

@push('styles')
<style>
.circle-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    border: 1px solid rgba(15, 23, 42, 0.08);
    transition: all 0.2s;
}
.circle-btn:hover {
    transform: scale(1.08);
}
.kpi-stat-card {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: transform 0.2s ease;
}
.kpi-stat-card:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-info">
        <h4 class="card-title">Módulo de Validación de Especialidades Médicas</h4>
    </div>

    <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('riiss.index') }}">RIISS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Validación de Especialidades Médicas (Hospitales Área Interior y Central)</li>
        </ol>
    </nav>

    <div class="container-fluid px-3">

        {{-- Alertas --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Tarjetas KPI de Resumen --}}
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #00bcd4 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Área Interior</div>
                            <div class="h3 font-weight-bold text-dark mb-0 mt-1">{{ $totalInterior }}</div>
                            <small class="text-muted">{{ count($deptosInterior) }} Departamentos</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-info">
                            <i class="fa fa-hospital fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #2196f3 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Área Central / Capital</div>
                            <div class="h3 font-weight-bold text-primary mb-0 mt-1">{{ $totalCentral }}</div>
                            <small class="text-muted">Central y Asunción</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-primary">
                            <i class="fa fa-city fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #4caf50 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Especialidades Validadas</div>
                            <div class="h3 font-weight-bold text-success mb-0 mt-1">{{ $totalRegistrosValidados }}</div>
                            <small class="text-success font-weight-bold">Confirmadas Activas</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-success">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-stat-card p-3 h-100" style="border-left: 4px solid #f44336 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Especialidades Inactivadas</div>
                            <div class="h3 font-weight-bold text-danger mb-0 mt-1">{{ $totalRegistrosInactivos }}</div>
                            <small class="text-muted">{{ $totalConRevision }} Centros Auditados</small>
                        </div>
                        <div class="bg-light p-3 rounded-circle text-danger">
                            <i class="fa fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Principal con Tabla de Enlaces --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white py-3">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <label class="font-weight-bold mr-2 mb-0 text-dark small">
                        <i class="fa fa-filter text-info mr-1"></i> Área:
                    </label>
                    <form method="GET" action="{{ route('riiss.validaciones.index') }}" class="form-inline">
                        <select name="area" class="form-control form-control-sm font-weight-bold mr-2" onchange="this.form.submit()" style="min-width: 170px;">
                            <option value="">📋 Todas las Áreas</option>
                            <option value="AREA INTERIOR" @selected(request('area') === 'AREA INTERIOR')>🏥 Área Interior ({{ $totalInterior }})</option>
                            <option value="AREA CENTRAL" @selected(request('area') === 'AREA CENTRAL')>🏙️ Área Central ({{ $totalCentral }})</option>
                        </select>
                    </form>
                </div>

                <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
                    <form method="GET" action="{{ route('riiss.validaciones.index') }}" class="form-inline mr-2">
                        @if(request('area'))
                            <input type="hidden" name="area" value="{{ request('area') }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <input type="text" name="buscar" class="form-control" placeholder="Buscar analista o código..." value="{{ request('buscar') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-info" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <button type="button" class="btn btn-outline-info font-weight-bold" data-toggle="modal" data-target="#modalClasificacionTerritorial">
                        <i class="fa fa-map-marked-alt mr-1"></i> Clasificación Territorial ({{ $totalEstablecimientos }})
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold" data-toggle="modal" data-target="#modalGenerarEnlace">
                        <i class="fa fa-plus mr-1"></i> Nuevo Enlace de Validador
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" style="width:100%;">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th style="width: 130px;" class="text-center">Código Acceso</th>
                                <th style="max-width: 250px;">Analista Responsable</th>
                                <th style="width: 200px;">Dirección / Alcance Asignado</th>
                                <th style="width: 120px;" class="text-center">Registros Guardados</th>
                                <th style="width: 100px;" class="text-center">Estado</th>
                                <th style="width: 180px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sesiones as $idx => $s)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info px-2 py-1 font-weight-bold" style="font-size:11.5px; letter-spacing:0.5px;">
                                            <i class="fa fa-key mr-1"></i> {{ $s->codigo_acceso }}
                                        </span>
                                        <div class="text-muted small mt-1" style="font-size:10.5px;">
                                            {{ $s->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $s->analista_nombre }}</div>
                                        <div class="text-muted small">{{ $s->analista_cargo ?: 'Analista Técnico' }}</div>
                                        @if($s->analista_documento || $s->analista_telefono)
                                            <div class="text-muted small" style="font-size:10.5px;">
                                                @if($s->analista_documento) C.I.: {{ $s->analista_documento }} @endif
                                                @if($s->analista_telefono) · Tel: {{ $s->analista_telefono }} @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($s->area_gestion === 'AREA CENTRAL')
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold">
                                                <i class="fa fa-city mr-1"></i> Área Central
                                            </span>
                                        @else
                                            <span class="badge badge-info px-2 py-1 font-weight-bold" style="background-color: #00bcd4;">
                                                <i class="fa fa-hospital mr-1"></i> Área Interior
                                            </span>
                                        @endif

                                        <div class="mt-1" style="font-size:11px;">
                                            @if($s->departamento_filtro)
                                                <span class="text-dark font-weight-bold">
                                                    <i class="fa fa-map-marker-alt text-danger mr-1"></i> {{ $s->departamento_filtro }}
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fa fa-globe-americas mr-1"></i> Todos los Dptos. del Área
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-weight-bold text-info" style="font-size:15px;">
                                            {{ $s->registros_count }}
                                        </span>
                                        <div class="text-muted small" style="font-size:10px;">especialidades</div>
                                    </td>
                                    <td class="text-center">
                                        @if($s->estado === 'finalizado')
                                            <span class="badge badge-success px-2 py-1 font-weight-bold">
                                                <i class="fa fa-check mr-1"></i> Finalizado
                                            </span>
                                        @else
                                            <span class="badge badge-light border border-success text-success px-2 py-1 font-weight-bold">
                                                <i class="fa fa-spinner fa-pulse mr-1"></i> Activo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center" style="gap: 6px;">
                                            {{-- Copiar enlace --}}
                                            <button type="button" 
                                                    class="circle-btn btn btn-outline-info btn-copy" 
                                                    data-url="{{ $s->url_acceso }}"
                                                    title="Copiar Enlace Directo">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                            
                                            {{-- Abrir portal --}}
                                            <a href="{{ $s->url_acceso }}" target="_blank" class="circle-btn btn btn-info text-white" title="Abrir Portal de Validación">
                                                <i class="fa fa-external-link-alt"></i>
                                            </a>

                                            {{-- Imprimir acta --}}
                                            <a href="{{ route('riiss.portal-validador.acta-imprimir', $s->token) }}" target="_blank" class="circle-btn btn btn-outline-secondary" title="Imprimir / Ver Acta Consolidada">
                                                <i class="fa fa-print"></i>
                                            </a>

                                            {{-- Eliminar --}}
                                            <form action="{{ route('riiss.validaciones.eliminar-enlace', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este enlace de validador?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="circle-btn btn btn-danger text-white" title="Eliminar Enlace">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa fa-link fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                                        <p class="mb-1 font-weight-bold">No hay enlaces de validadores generados aún.</p>
                                        <p class="small">Haga clic en <strong>"+ Nuevo Enlace de Validador"</strong> para emitir el primer acceso de relevamiento.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($sesiones->hasPages())
                <div class="card-footer bg-white d-flex justify-content-center py-3">
                    {{ $sesiones->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Modal Generar Enlace con Segmentación Territorial --}}
<div class="modal fade" id="modalGenerarEnlace" tabindex="-1" role="dialog" aria-labelledby="modalGenerarEnlaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('riiss.validaciones.generar-enlace') }}" method="POST">
                @csrf
                <div class="modal-header py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #00acc1 0%, #26c6da 100%); color: #ffffff; border-radius: calc(0.3rem - 1px) calc(0.3rem - 1px) 0 0;">
                    <h5 class="modal-title font-weight-bold text-white mb-0" id="modalGenerarEnlaceLabel">
                        <i class="fa fa-key mr-2 text-white"></i> Generar Enlace Único de Validador
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none;">
                        <span aria-hidden="true" style="font-size: 1.5rem; color: #ffffff;">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Seleccione el Área de Gestión y los datos del analista responsable para emitir el enlace único de relevamiento.
                    </p>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Dirección / Área de Gestión <span class="text-danger">*</span></label>
                        <select name="area_gestion" id="selectAreaGestion" class="form-control font-weight-bold" required onchange="actualizarOpcionesDepartamentos()">
                            <option value="AREA INTERIOR" selected>DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR ({{ $totalInterior }} Hospitales)</option>
                            <option value="AREA CENTRAL">DIRECCIÓN DE HOSPITALES DEL ÁREA CENTRAL ({{ $totalCentral }} Centros)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Alcance / Departamento Asignado</label>
                        <select name="departamento_filtro" id="selectDeptoFiltro" class="form-control">
                            {{-- Opciones inyectadas dinámicamente según el área seleccionada --}}
                        </select>
                        <small class="text-muted" id="ayudaAlcance">El analista solo verá los establecimientos del área y departamento seleccionados.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nombre y Apellido del Analista / Responsable <span class="text-danger">*</span></label>
                        <input type="text" name="analista_nombre" class="form-control" required placeholder="Ej: Lic. Carlos Gómez">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Cargo / Función</label>
                        <input type="text" name="analista_cargo" id="inputAnalistaCargo" class="form-control" placeholder="Ej: Analista Técnico Área Interior" value="Analista Técnico Área Interior">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Cédula de Identidad (C.I.)</label>
                            <input type="text" name="analista_documento" class="form-control" placeholder="Ej: 3.456.789">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold text-dark">Teléfono / WhatsApp</label>
                            <input type="text" name="analista_telefono" class="form-control" placeholder="Ej: 0981 123456">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Notas u Observaciones (Opcional)</label>
                        <textarea name="notas" class="form-control" rows="2" placeholder="Indicaciones sobre la campaña de relevamiento..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fa fa-link mr-1"></i> Generar y Emitir Enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Clasificación Territorial (Área Central vs Área Interior) --}}
<div class="modal fade" id="modalClasificacionTerritorial" tabindex="-1" role="dialog" aria-labelledby="modalClasificacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3 px-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #00acc1 0%, #26c6da 100%); color: #ffffff; border-radius: calc(0.3rem - 1px) calc(0.3rem - 1px) 0 0;">
                <h5 class="modal-title font-weight-bold text-white mb-0" id="modalClasificacionLabel">
                    <i class="fa fa-map-marked-alt mr-2 text-white"></i> Clasificación Territorial de Establecimientos (Área Central vs Interior)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true" style="font-size: 1.5rem; color: #ffffff;">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fa fa-search text-muted"></i></span>
                            </div>
                            <input type="text" id="buscarEstablecimientoClasif" class="form-control" placeholder="Buscar establecimiento por nombre o código...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="filtroAreaClasif" class="form-control form-control-sm font-weight-bold">
                            <option value="">📋 Todas las Áreas</option>
                            <option value="AREA INTERIOR">🏥 Solo Área Interior</option>
                            <option value="AREA CENTRAL">🏙️ Solo Área Central</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-right">
                        <span class="badge badge-light border px-2 py-1" style="font-size:12px;">
                            Total: <strong>{{ $totalEstablecimientos }}</strong> centros
                        </span>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                    <table class="table table-bordered table-sm table-hover align-middle mb-0" id="tablaClasificacionEst">
                        <thead class="sticky-top" style="background: #1e293b; color: #ffffff; font-size:11.5px;">
                            <tr>
                                <th style="width: 12%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Código</th>
                                <th style="width: 38%; background: #1e293b; color: #ffffff; border-color: #334155;">Establecimiento de Salud</th>
                                <th style="width: 22%; background: #1e293b; color: #ffffff; border-color: #334155;">Departamento / Tipología</th>
                                <th style="width: 28%; background: #1e293b; color: #ffffff; border-color: #334155;" class="text-center">Área de Gestión Asignada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todosEstablecimientos as $e)
                                <tr class="fila-est-clasif" 
                                    data-nombre="{{ strtolower($e->nombre_oficial . ' ' . $e->id_establecimiento) }}"
                                    data-area="{{ $e->area_gestion }}">
                                    <td class="text-center font-weight-bold text-muted" style="font-size:11px;">
                                        {{ $e->id_establecimiento }}
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark" style="font-size:12.5px;">
                                            {{ $e->nombre_oficial }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-dark">{{ $e->departamento }}</span>
                                        <div class="text-muted small" style="font-size:10.5px;">{{ $e->tipologia_clasificacion }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                                            <label class="btn {{ $e->area_gestion === 'AREA INTERIOR' ? 'btn-success active' : 'btn-outline-secondary' }} btn-sm font-weight-bold" 
                                                   onclick="cambiarAreaEstablecimiento('{{ $e->id_establecimiento }}', 'AREA INTERIOR')">
                                                <input type="radio" name="area_{{ $e->id_establecimiento }}" autocomplete="off" @checked($e->area_gestion === 'AREA INTERIOR')>
                                                Área Interior
                                            </label>
                                            <label class="btn {{ $e->area_gestion === 'AREA CENTRAL' ? 'btn-info active' : 'btn-outline-secondary' }} btn-sm font-weight-bold" 
                                                   onclick="cambiarAreaEstablecimiento('{{ $e->id_establecimiento }}', 'AREA CENTRAL')">
                                                <input type="radio" name="area_{{ $e->id_establecimiento }}" autocomplete="off" @checked($e->area_gestion === 'AREA CENTRAL')>
                                                Área Central
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const DEPTOS_INTERIOR = @json($deptosInterior);
const DEPTOS_CENTRAL = @json($deptosCentral);

function actualizarOpcionesDepartamentos() {
    const area = $('#selectAreaGestion').val();
    const $selectDepto = $('#selectDeptoFiltro');
    const $cargo = $('#inputAnalistaCargo');

    let html = '';
    if (area === 'AREA CENTRAL') {
        $cargo.val('Analista Técnico Área Central');
        html += '<option value="TODOS_CENTRAL">TODOS LOS DEPARTAMENTOS DE ÁREA CENTRAL (Central y Asunción)</option>';
        DEPTOS_CENTRAL.forEach(d => {
            html += `<option value="${d}">${d}</option>`;
        });
    } else {
        $cargo.val('Analista Técnico Área Interior');
        html += `<option value="TODOS_INTERIOR">TODOS LOS DEPARTAMENTOS DEL ÁREA INTERIOR (${DEPTOS_INTERIOR.length} Dptos)</option>`;
        DEPTOS_INTERIOR.forEach(d => {
            html += `<option value="${d}">${d}</option>`;
        });
    }

    $selectDepto.html(html);
}

function cambiarAreaEstablecimiento(estId, nuevaArea) {
    $.post('{{ route("riiss.validaciones.establecimiento.area-gestion") }}', {
        _token: '{{ csrf_token() }}',
        establecimiento_id: estId,
        area_gestion: nuevaArea
    }, function(res) {
        if (res.success) {
            $(`tr[data-nombre*="${estId.toLowerCase()}"]`).attr('data-area', nuevaArea);
        }
    }).fail(function() {
        alert('Error al actualizar el área de gestión del establecimiento.');
    });
}

$(document).ready(function() {
    actualizarOpcionesDepartamentos();

    // Copiar enlace
    $('.btn-copy').on('click', function() {
        var url = $(this).data('url');
        var $btn = $(this);
        navigator.clipboard.writeText(url).then(function() {
            var origHtml = $btn.html();
            $btn.removeClass('btn-outline-info').addClass('btn-success').html('<i class="fa fa-check"></i>');
            setTimeout(function() {
                $btn.removeClass('btn-success').addClass('btn-outline-info').html(origHtml);
            }, 2500);
        }).catch(function(err) {
            alert('Enlace: ' + url);
        });
    });

    // Filtros modal clasificación
    $('#buscarEstablecimientoClasif, #filtroAreaClasif').on('input change', function() {
        const query = $('#buscarEstablecimientoClasif').val().toLowerCase().trim();
        const areaFiltro = $('#filtroAreaClasif').val();

        $('.fila-est-clasif').each(function() {
            const nombre = $(this).data('nombre');
            const area = $(this).attr('data-area');

            const matchQuery = !query || nombre.includes(query);
            const matchArea = !areaFiltro || area === areaFiltro;

            if (matchQuery && matchArea) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>
@endpush
