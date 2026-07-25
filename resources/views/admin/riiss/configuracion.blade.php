@extends('layouts.master')
@section('title', 'Configuración RIISS')

@push('styles')
<style>
.riiss-tabs .nav-link {
    font-weight: 600;
    color: #475569;
    border-radius: 8px;
    padding: 10px 18px;
    margin-right: 6px;
    transition: all .2s;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}
.riiss-tabs .nav-link.active {
    background: linear-gradient(135deg, #1e293b, #334155) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 4px 12px rgba(30, 41, 59, .25);
}
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header card-header-info py-3" style="background:linear-gradient(135deg,#0f172a,#1e293b)">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h4 class="card-title mb-0 text-white">
                    <i class="fa fa-cog mr-2"></i>Configuración del Módulo RIISS
                </h4>
                <p class="card-category mb-0 text-white-50">Administración técnica de formularios, reglas por tipología y complejidad</p>
            </div>
            <div>
                <a href="{{ route('riiss.index') }}" class="btn btn-outline-light btn-sm font-weight-bold">
                    <i class="fa fa-arrow-left mr-1"></i>Volver al Centro RIISS
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body pb-2">
        <ul class="nav nav-pills riiss-tabs border-0" id="riissConfigTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-formularios-tab" data-toggle="tab" href="#tab-formularios" role="tab">
                    <i class="fa fa-file-alt mr-1"></i>Formularios Dinámicos & Preguntas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-complejidad-tab" data-toggle="tab" href="#tab-complejidad" role="tab">
                    <i class="fa fa-weight-hanging mr-1"></i>Ponderación & Complejidad
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="riissConfigTabsContent">

    {{-- TAB 1: Formularios Dinámicos --}}
    <div class="tab-pane fade show active" id="tab-formularios" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Gestión de Tipologías y Preguntas</h5>
                    <a href="{{ route('riiss.formularios.index') }}" class="btn btn-sm btn-info">
                        <i class="fa fa-external-link-alt mr-1"></i>Abrir Editor de Formularios
                    </a>
                </div>
                <p class="text-muted">
                    Aquí puedes configurar las secciones y preguntas aplicables para cada tipo de establecimiento de la red de salud.
                </p>
            </div>
        </div>
    </div>

    {{-- TAB 2: Complejidad --}}
    <div class="tab-pane fade" id="tab-complejidad" role="tabpanel">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-weight-bold mb-0">Matriz de Complejidad Hospitalaria</h5>
                    <a href="{{ route('riiss.complejidad.index') }}" class="btn btn-sm btn-dark">
                        <i class="fa fa-external-link-alt mr-1"></i>Abrir Matriz de Complejidad
                    </a>
                </div>
                <p class="text-muted">
                    Ajusta los ponderadores e indicadores de nivel de complejidad para los hospitales y centros de salud.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
