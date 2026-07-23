@extends('layouts.master')
@section('title', 'Configuración del Dashboard')

@section('content')
<div class="card shadow-sm">
    <div class="card-header card-header-info py-3">
        <h4 class="card-title mb-0">
            <i class="fa fa-cog mr-2"></i>Configuración del Dashboard
        </h4>
        <p class="card-category mb-0 text-muted">
            Personaliza qué módulos y datos se muestran en el inicio
        </p>
    </div>

    <nav aria-label="breadcrumb" class="bg-white rounded-0 p-3 mb-0">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Inicio</a></li>
            <li class="breadcrumb-item active">Configuración</li>
        </ol>
    </nav>

    <div class="card-body">
        <form id="configForm" method="POST" action="{{ route('home-config.update') }}">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Módulo FODA --}}
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fa fa-search mr-2 text-danger"></i>Módulo FODA
                                </h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_foda" id="show_foda" value="1" {{ $config->show_foda ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_foda">Mostrar</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Perfil FODA Consolidado:</label>
                                <select class="form-control select2" name="foda_profile_id" id="foda_profile_id" {{ !$config->show_foda ? 'disabled' : '' }}>
                                    <option value="">-- Seleccionar FODA --</option>
                                    @foreach($fodaProfiles as $fp)
                                        <option value="{{ $fp->id }}" {{ $config->foda_profile_id == $fp->id ? 'selected' : '' }}>
                                            {{ $fp->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Selecciona el FODA consolidado que se mostrará en el dashboard
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Análisis FODA:</label>
                                <select class="form-control select2" name="foda_analisis_id" id="foda_analisis_id" {{ !$config->show_foda ? 'disabled' : '' }}>
                                    <option value="">-- Seleccionar Análisis --</option>
                                    @foreach($fodaAnalisis as $fa)
                                        <option value="{{ $fa->id }}" {{ $config->foda_analisis_id == $fa->id ? 'selected' : '' }}>
                                            {{ $fa->created_at->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Selecciona el análisis específico del FODA
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Módulo PEI --}}
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fa fa-chart-line mr-2 text-info"></i>Módulo PEI
                                </h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_pei" id="show_pei" value="1" {{ $config->show_pei ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_pei">Mostrar</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Plan Estratégico Corporativo:</label>
                                <select class="form-control select2" name="pei_profile_id" id="pei_profile_id" {{ !$config->show_pei ? 'disabled' : '' }}>
                                    <option value="">-- Seleccionar PEI --</option>
                                    @foreach($peiProfiles as $pp)
                                        <option value="{{ $pp->id }}" {{ $config->pei_profile_id == $pp->id ? 'selected' : '' }}>
                                            {{ $pp->name }}
                                            ({{ \Carbon\Carbon::parse($pp->year_start)->format('Y') }}–{{ \Carbon\Carbon::parse($pp->year_end)->format('Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Selecciona el PEI corporativo que se mostrará en el dashboard
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Módulo RIISS --}}
                <div class="col-md-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold">
                                    <i class="fa fa-hospital-o mr-2 text-primary"></i>Módulo RIISS
                                </h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_riiss" id="show_riiss" value="1" {{ $config->show_riiss ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_riiss">Mostrar</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                El módulo RIISS mostrará automáticamente:
                                <i class="fa fa-check text-success mr-1"></i>
                                Total de establecimientos del IPS
                                <i class="fa fa-check text-success ml-3 mr-1"></i>
                                Establecimientos analizados
                                <i class="fa fa-check text-success ml-3 mr-1"></i>
                                Últimas evaluaciones con nombre del evaluador
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fa fa-times mr-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save mr-1"></i>Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
@stop

@section('scripts')
<script>
$(function() {
    // Inicializar Select2
    $('.select2').select2({ width: '100%' });

    // Habilitar/deshabilitar selects según checkboxes
    function toggleFields() {
        var showFoda = $('#show_foda').is(':checked');
        var showPei = $('#show_pei').is(':checked');
        var showRiiss = $('#show_riiss').is(':checked');

        $('#foda_profile_id').prop('disabled', !showFoda);
        $('#foda_analisis_id').prop('disabled', !showFoda);
        $('#pei_profile_id').prop('disabled', !showPei);
    }

    $('#show_foda, #show_pei, #show_riiss').on('change', toggleFields);
    toggleFields();
});
</script>
@stop
