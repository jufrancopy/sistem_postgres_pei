@extends('layouts.master')

@section('title', 'Gestión de Enlaces y Relevamiento — Hospitales Área Interior')

@section('content')
<div class="content">
    <div class="container-fluid">

        {{-- Alertas --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Cabecera Principal --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white shadow-sm border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0369a1 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div>
                                <span class="badge badge-info px-3 py-1 text-uppercase" style="letter-spacing:1px; font-size:11px;">
                                    <i class="fa fa-stethoscope mr-1"></i> DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR
                                </span>
                                <h2 class="font-weight-bold mt-2 mb-1 text-white">Validación de Especialidades Médicas</h2>
                                <p class="text-light mb-0 font-weight-300" style="font-size:14px; opacity: 0.9;">
                                    Generador de enlaces con código único para analistas y monitoreo en tiempo real del relevamiento por establecimiento.
                                </p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <button type="button" class="btn btn-success btn-lg shadow font-weight-bold" data-toggle="modal" data-target="#modalGenerarEnlace">
                                    <i class="fa fa-plus-circle mr-1"></i> Generar Nuevo Enlace de Validador
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPIs de Avance --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0284c7 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small font-weight-bold">Establecimientos Totales</div>
                                <div class="h3 font-weight-bold text-dark mb-0 mt-1">{{ $totalEstablecimientos }}</div>
                                <small class="text-muted">Red Asistencial Interior</small>
                            </div>
                            <div class="bg-light p-3 rounded-circle text-primary">
                                <i class="fa fa-hospital fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small font-weight-bold">Especialidades Validadas</div>
                                <div class="h3 font-weight-bold text-success mb-0 mt-1">{{ $totalRegistrosValidados }}</div>
                                <small class="text-success font-weight-bold">Confirmadas Activas</small>
                            </div>
                            <div class="bg-light p-3 rounded-circle text-success">
                                <i class="fa fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ef4444 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small font-weight-bold">Especialidades Inactivadas</div>
                                <div class="h3 font-weight-bold text-danger mb-0 mt-1">{{ $totalRegistrosInactivos }}</div>
                                <small class="text-muted">No existentes en centro</small>
                            </div>
                            <div class="bg-light p-3 rounded-circle text-danger">
                                <i class="fa fa-times-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #8b5cf6 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small font-weight-bold">Centros con Relevamiento</div>
                                <div class="h3 font-weight-bold text-purple mb-0 mt-1" style="color:#7c3aed;">{{ $totalConRevision }}</div>
                                <small class="text-muted">Hospitales auditados</small>
                            </div>
                            <div class="bg-light p-3 rounded-circle text-purple" style="color:#7c3aed;">
                                <i class="fa fa-clipboard-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Enlaces Generados para Validadores --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-2 mb-md-0 font-weight-bold text-dark">
                            <i class="fa fa-link text-primary mr-2"></i> Enlaces y Accesos de Validadores Asignados
                        </h5>
                        
                        <form method="GET" action="{{ route('riiss.validaciones.index') }}" class="form-inline">
                            <div class="input-group input-group-sm">
                                <input type="text" name="buscar" class="form-control" placeholder="Buscar por analista o código..." value="{{ request('buscar') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light" style="background-color: #f8fafc; font-size:12px;">
                                <tr>
                                    <th style="width: 14%;">Código de Acceso</th>
                                    <th style="width: 25%;">Analista Responsable</th>
                                    <th style="width: 18%;">Alcance Territorial</th>
                                    <th style="width: 13%;" class="text-center">Registros Guardados</th>
                                    <th style="width: 10%;" class="text-center">Estado</th>
                                    <th style="width: 20%;" class="text-right">Acciones y Enlace</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sesiones as $s)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size:12px; letter-spacing:0.5px; background-color:#0284c7;">
                                                <i class="fa fa-key mr-1"></i> {{ $s->codigo_acceso }}
                                            </span>
                                            <div class="text-muted small mt-1" style="font-size:11px;">
                                                Creado: {{ $s->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $s->analista_nombre }}</div>
                                            <div class="text-muted small">{{ $s->analista_cargo ?: 'Analista Área Interior' }}</div>
                                            @if($s->analista_documento || $s->analista_telefono)
                                                <div class="text-muted small" style="font-size:10.5px;">
                                                    @if($s->analista_documento) C.I.: {{ $s->analista_documento }} @endif
                                                    @if($s->analista_telefono) · Tel: {{ $s->analista_telefono }} @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($s->departamento_filtro)
                                                <span class="badge badge-info px-2 py-1">
                                                    <i class="fa fa-map-marker-alt mr-1"></i> {{ $s->departamento_filtro }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">
                                                    <i class="fa fa-globe-americas mr-1"></i> Todos los Departamentos
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="font-weight-bold text-primary" style="font-size:14px;">
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
                                        <td class="text-right">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-copy" 
                                                        data-url="{{ $s->url_acceso }}"
                                                        title="Copiar Enlace Directo para enviar al Validador">
                                                    <i class="fa fa-copy mr-1"></i> Copiar Enlace
                                                </button>
                                                
                                                <a href="{{ $s->url_acceso }}" target="_blank" class="btn btn-primary" title="Abrir Portal de Validación">
                                                    <i class="fa fa-external-link-alt"></i>
                                                </a>

                                                <a href="{{ route('riiss.portal-validador.acta-imprimir', $s->token) }}" target="_blank" class="btn btn-outline-secondary" title="Imprimir / Ver Acta Consolidada">
                                                    <i class="fa fa-print"></i>
                                                </a>

                                                <form action="{{ route('riiss.validaciones.eliminar-enlace', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este enlace de validador?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar Enlace">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fa fa-link fa-3x mb-3 text-secondary" style="opacity: 0.5;"></i>
                                            <p class="mb-1 font-weight-bold">No hay enlaces de validadores generados aún.</p>
                                            <p class="small">Haga clic en el botón <strong>"Generar Nuevo Enlace de Validador"</strong> para crear el primer acceso de relevamiento.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($sesiones->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-center">
                            {{ $sesiones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Generar Enlace --}}
<div class="modal fade" id="modalGenerarEnlace" tabindex="-1" role="dialog" aria-labelledby="modalGenerarEnlaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('riiss.validaciones.generar-enlace') }}" method="POST">
                @csrf
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);">
                    <h5 class="modal-title font-weight-bold" id="modalGenerarEnlaceLabel">
                        <i class="fa fa-key mr-2"></i> Generar Enlace Único de Validador
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Complete los datos del analista responsable de la Dirección de Hospitales del Área Interior para emitir el enlace único de relevamiento.
                    </p>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nombre y Apellido del Analista / Responsable <span class="text-danger">*</span></label>
                        <input type="text" name="analista_nombre" class="form-control" required placeholder="Ej: Lic. Carlos Gómez">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Cargo / Función</label>
                        <input type="text" name="analista_cargo" class="form-control" placeholder="Ej: Analista Técnico Área Interior" value="Analista Técnico Área Interior">
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

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Alcance / Departamento Asignado</label>
                        <select name="departamento_filtro" class="form-control">
                            <option value="TODOS">TODOS LOS DEPARTAMENTOS (Área Interior)</option>
                            @foreach($departamentos as $dpto)
                                <option value="{{ $dpto }}">{{ $dpto }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Si selecciona "TODOS", el analista podrá auditar cualquier establecimiento del país.</small>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Notas u Observaciones (Opcional)</label>
                        <textarea name="notas" class="form-control" rows="2" placeholder="Indicaciones sobre la campaña de relevamiento..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-link mr-1"></i> Generar y Emitir Enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.btn-copy').on('click', function() {
        var url = $(this).data('url');
        var $btn = $(this);
        navigator.clipboard.writeText(url).then(function() {
            var origHtml = $btn.html();
            $btn.removeClass('btn-outline-primary').addClass('btn-success').html('<i class="fa fa-check mr-1"></i> ¡Copiado!');
            setTimeout(function() {
                $btn.removeClass('btn-success').addClass('btn-outline-primary').html(origHtml);
            }, 2500);
        }).catch(function(err) {
            alert('Enlace: ' + url);
        });
    });
});
</script>
@endpush
