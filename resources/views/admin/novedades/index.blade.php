@extends('layouts.master')

@section('title', 'Historial de Desarrollos y Novedades del Sistema')

@section('content')
<div class="content-wrapper p-3 p-md-4 bg-light">
    <div class="card">
        <div class="card-header card-header-info">
            <h4 class="card-title text-white font-weight-bold mb-0">
                <i class="fas fa-rocket mr-2"></i> Historial de Desarrollos, Mejoras y Novedades del Sistema
            </h4>
        </div>

        <nav aria-label="breadcrumb" class="bg-ligth rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('planificacion-dashboard') }}">Planificación-Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Novedades y Desarrollos</li>
            </ol>
        </nav>

        <div class="row px-3">
            {{-- Stat Cards --}}
            @php
                $totalCommits = count($commits);
                $totalFeat = count(array_filter($commits, fn($c) => str_contains($c['categoria'], 'NUEVA')));
                $totalFix = count(array_filter($commits, fn($c) => str_contains($c['categoria'], 'CORRECCIÓN')));
                $totalUi = count(array_filter($commits, fn($c) => str_contains($c['categoria'], 'DISEÑO')));
            @endphp
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm text-white" style="border-radius: 14px; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 small font-weight-bold text-uppercase d-block">Total Avanzados</span>
                            <h3 class="font-weight-bold mb-0 text-warning">{{ number_format($totalCommits) }}</h3>
                        </div>
                        <i class="fas fa-code-branch fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm text-white" style="border-radius: 14px; background: linear-gradient(135deg, #15803d 0%, #166534 100%);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 small font-weight-bold text-uppercase d-block">Funcionalidades</span>
                            <h3 class="font-weight-bold mb-0 text-white">{{ number_format($totalFeat) }}</h3>
                        </div>
                        <i class="fas fa-magic fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm text-white" style="border-radius: 14px; background: linear-gradient(135deg, #b45309 0%, #92400e 100%);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 small font-weight-bold text-uppercase d-block">Correcciones / Fixes</span>
                            <h3 class="font-weight-bold mb-0 text-white">{{ number_format($totalFix) }}</h3>
                        </div>
                        <i class="fas fa-bug fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm text-white" style="border-radius: 14px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 small font-weight-bold text-uppercase d-block">Diseño & UI</span>
                            <h3 class="font-weight-bold mb-0 text-white">{{ number_format($totalUi) }}</h3>
                        </div>
                        <i class="fas fa-palette fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3 border-bottom">
                        <h5 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-history text-info mr-2"></i> Registro Transparente de Aportes del Equipo de Desarrollo (Git Log)
                        </h5>
                        <span class="badge badge-light border text-muted px-3 py-1 font-weight-bold">
                            Sincronizado con repositorio Git
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover data-table text-dark" id="tablaNovedadesGit" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 140px;" class="text-center">Hash / Tipo</th>
                                        <th style="width: 180px;">Desarrollador</th>
                                        <th>Mejora / Descripción del Avance</th>
                                        <th style="width: 140px;" class="text-center">Fecha Despliegue</th>
                                        <th style="width: 100px;" class="text-center">GitHub</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commits as $c)
                                        <tr>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-dark font-mono font-weight-bold px-2 py-1 mb-1 d-block" style="font-family: monospace; font-size: 0.8rem;">
                                                    {{ $c['hash'] }}
                                                </span>
                                                <span class="badge {{ $c['badge_class'] }} font-weight-bold px-2 py-0.5" style="font-size: 0.68rem;">
                                                    {{ $c['categoria'] }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <div class="rounded-circle bg-primary text-white font-weight-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.82rem;">
                                                        {{ $c['iniciales'] }}
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark d-block" style="font-size: 0.88rem; line-height: 1.2;">{{ $c['author'] }}</strong>
                                                        <small class="text-muted" style="font-size: 0.72rem;">{{ $c['email'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="font-weight-bold text-dark d-block" style="font-size: 0.92rem; line-height: 1.4;">
                                                    {{ $c['subject'] }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center small text-muted font-weight-bold">
                                                <i class="far fa-calendar-alt mr-1 text-primary"></i> {{ $c['date'] }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ $c['github_url'] }}" target="_blank" class="btn btn-xs btn-outline-dark font-weight-bold px-2 py-1" style="font-size: 0.72rem; border-radius: 6px;" title="Ver commit en GitHub">
                                                    <i class="fab fa-github mr-1"></i> {{ $c['hash'] }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#tablaNovedadesGit').DataTable({
            responsive: true,
            pageLength: 15,
            lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "Todos"]],
            order: [],
            language: {
                search: "Buscar novedad:",
                searchPlaceholder: "Filtrar desarrollos...",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ desarrollos",
                infoEmpty: "No hay registros",
                infoFiltered: "(filtrado de _MAX_ desarrollos en total)",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }
});
</script>
@endpush
@endsection
